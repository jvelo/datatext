<?php

namespace Jvelo\Datatext\Models;

use Illuminate\Database\Eloquent\Model;
use Jvelo\Datatext\Database\CompositeKey;

class ObjectRevision extends Model {

    use CompositeKey;

    protected $table = 'object_revision';

    protected $primaryKey = ['object_id','revision_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'object_id' => 'string'
    ];

}