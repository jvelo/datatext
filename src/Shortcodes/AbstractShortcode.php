<?php

namespace Jvelo\Datatext\Shortcodes;


abstract class AbstractShortcode implements Shortcode {

    public function listeners()
    {
        return [];
    }
}
