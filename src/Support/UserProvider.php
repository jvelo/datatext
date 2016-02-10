<?php

namespace Jvelo\Datatext\Support;

use Illuminate\Support\Facades\Facade;

class UserProvider extends Facade {

    protected static function getFacadeAccessor() { return 'datatext.user_provider'; }

}
