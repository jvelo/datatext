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
use Jvelo\Datatext\Support\Shortcodes;
use Jvelo\Datatext\Support\UserProvider;
use Jvelo\Datatext\Markdown\Markdown;
use Eloquent\Dialect\Json;
use Cocur\Slugify\Slugify;
use Carbon\Carbon;
use DiffMatchPatch\DiffMatchPatch;
use Illuminate\Support\Facades\DB;
use mikemccabe\JsonPatch\JsonPatch;
use Masterminds\HTML5;

class Page extends Model {

    use UuidKey;
    use Json;

    protected $table = 'page';

    protected $jsonColumns = ['metadata'];

    public $timestamps = false;

    protected $appends = ['html_content'];

    protected $casts = [
        'id' => 'string'
    ];

    protected $fillable = ['title', 'tags', 'content'];

    public function __construct()
    {
        parent::__construct();
        $this->hintJsonStructure('metadata', '{"title":null, "author": null, "tags" : []}');
        $this->metadata = '{}';
    }

     public function getHtmlContentAttribute()
     {
         if (!isset($this->parser)) {
             $this->parser = new Markdown();
         }

         $html = Shortcodes::process($this->attributes['content']);
         $html =$this->parser->text($html);

         return $html;
     }

    /**
     * Ensure the save is being done in a transaction so that pages are always consistent with their revisions (saved
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

        static::creating(function($page)
        {
            $page->setJsonAttribute('metadata', 'author', UserProvider::getCurrentUserId());
        });

        static::created(function($page)
        {
            $revision = new Revision;
            $revision->page_id = $page->id;
            $revision->revision_id = 0;

            $dmp = new DiffMatchPatch();

            // Content patch
            $contentPatches = $dmp->patch_make("", $page->content);
            $revision->content_patch = $dmp->patch_toText($contentPatches);

            // Metadata patch
            $revision->metadata_patch = json_encode(JsonPatch::diff([], json_decode($page->metadata, 1)));

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });

        static::updating(function($updatingPage){
            // Get the page again from DB so we are sure we have up-to-date content to make the diff from
            $page = Page::find($updatingPage->id);

            $lastRevision = Revision::where('page_id', $page->id)->orderBy('revision_id','DESC')->firstOrFail();

            $revision = new Revision;
            $revision->page_id = $page->id;
            $revision->revision_id = $lastRevision->revision_id + 1;

            $dmp = new DiffMatchPatch();

            // Content patch
            $contentPatches = $dmp->patch_make($page->getOriginal('content'), $updatingPage->content);
            $revision->content_patch = $dmp->patch_toText($contentPatches);

            // Metadata patch
            $revision->metadata_patch = json_encode(
                JsonPatch::diff(json_decode($page->metadata, 1), json_decode($updatingPage->metadata, 1))
            );

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });
    }
}
