<?php

namespace Jvelo\Datatext\Shortcodes;


class Identity extends AbstractShortcode {

    public function name()
    {
        return "identity";
    }

    public function handler()
    {
        return function($shortcode) {
            return $shortcode->getContent();
        };

    }
}
