<?php

namespace Jvelo\Paidia\Support;

use Illuminate\Support\Facades\Facade;

class UserProvider extends Facade {

    protected static function getFacadeAccessor() { return 'paidia.user_provider'; }

}