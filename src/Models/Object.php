<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent\Dialect\Json;
use Jvelo\Eloquent\UuidKey;

class Object extends Model {

    use UuidKey;
    use Json;

    protected $table = 'object';

    protected $jsonColumns = ['data', 'metadata'];

    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
        $this->data = '{}';
        $this->metadata = '{}';
    }

}