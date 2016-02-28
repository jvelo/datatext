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
