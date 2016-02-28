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
