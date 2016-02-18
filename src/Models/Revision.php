<?php

namespace Jvelo\Datatext\Models;
use Illuminate\Database\Eloquent\Model;
use Jvelo\Datatext\Database\CompositeKey;

class Revision extends Model {

    use CompositeKey;

    protected $table = 'page_revision';

    protected $primaryKey = ['page_id','revision_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'page_id' => 'string'
    ];

}
