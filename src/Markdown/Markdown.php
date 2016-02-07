<?php

namespace Jvelo\Paidia\Markdown;

use Parsedown;
use Illuminate\Support\Facades\Log;

class Markdown extends Parsedown
{
    protected function blockTable($Line, array $Block = null)
    {
        $t = parent::blockTable($Line, $Block);
        // var_dump($t['element']);
        // var_dump($t['element']['name']); // => 'table';
        //$t['element']['name'] = 'table'; // makes the array empty. Why ?
        return $t ;
    }
}