<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Models;

use Illuminate\Database\Eloquent\Model;
use Jvelo\Eloquent\UuidKey;
use Jvelo\Datatext\Support\UserProvider;
use Eloquent\Dialect\Json;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use mikemccabe\JsonPatch\JsonPatch;


class Object extends Model
{
    use UuidKey;
    use Json;

    protected $table = 'object';

    protected $jsonColumns = ['data', 'metadata'];

    public $timestamps = false;

    protected $casts = [
        'id' => 'string'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->data = '{}';
        $this->metadata = '{}';
    }

    /**
     * Ensure the save is being done in a transaction so that objects are always consistent with their revisions (saved
     * in the creating/updating event callbacks, see below.
     *
     * @inheritDoc
     *
     * @param array $options
     * @return mixed
     */
    public function save(array $options = [])
    {
        return DB::transaction(function () use ($options) {
            return parent::save($options);
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($object) {
            $object->setJsonAttribute('metadata', 'author', UserProvider::getCurrentUserId());
        });

        static::created(function ($object) {
            $revision = new ObjectRevision;
            $revision->object_id = $object->id;
            $revision->revision_id = 0;

            // Data patch
            $revision->data_patch = json_encode(JsonPatch::diff([], json_decode($object->data, 1)));

            // Metadata patch
            $revision->metadata_patch = json_encode(JsonPatch::diff([], json_decode($object->metadata, 1)));

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });

        static::updating(function ($updatingObject) {
            // Get the object again from DB so we are sure we have up-to-date content to make the diff from
            $object = Object::find($updatingObject->id);

            var_dump("here updating");

            $lastRevision = ObjectRevision::where('object_id', $object->id)->orderBy('revision_id', 'DESC')->firstOrFail();

            $revision = new ObjectRevision;
            $revision->object_id = $object->id;
            $revision->revision_id = $lastRevision->revision_id + 1;

            // Data patch
            $revision->data_patch = json_encode(
                JsonPatch::diff(json_decode($object->data, 1), json_decode($updatingObject->data, 1)));

            // Metadata patch
            $revision->metadata_patch = json_encode(
                JsonPatch::diff(json_decode($object->metadata, 1), json_decode($updatingObject->metadata, 1)));

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });
    }

}
