<?php
namespace Jvelo\Datatext\Support;

use Illuminate\Support\Facades\Facade;

class Assets extends Facade {

    protected static function getFacadeAccessor() { return 'datatext.assets_manager'; }

}
