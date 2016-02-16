<?php

namespace Jvelo\Datatext\Markdown;

use Parsedown;
use Illuminate\Support\Facades\Log;

class Markdown extends Parsedown
{

    protected function blockTable($Line, array $Block = null)
    {
        $Table = parent::blockTable($Line, $Block);

        if (!is_array($Table)) {
            return $Table;
        }

        $Table['element']['attributes']['class'] = 'table';
        return $Table;
    }

}
