<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Support\Api;

use Illuminate\Support\Facades\Facade;

class Pages extends Facade {

    protected static function getFacadeAccessor() { return 'datatext.api.pages'; }

}
