<?php
namespace Jvelo\Datatext\Support;

use Illuminate\Support\Facades\Facade;

class Shortcodes extends Facade {

    protected static function getFacadeAccessor() { return 'datatext.shortcodes'; }

}
