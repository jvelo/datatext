<?php

namespace Jvelo\Datatext\Support\Api;

use Illuminate\Support\Facades\Facade;

class Pages extends Facade {

    protected static function getFacadeAccessor() { return 'datatext.api.pages'; }

}
