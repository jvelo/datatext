<?php

namespace Jvelo\Paidia\Shortcodes;


abstract class AbstractShortcode implements Shortcode {

    public function listeners()
    {
        return [];
    }
}