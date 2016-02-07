<?php

namespace Jvelo\Paidia\Shortcodes;


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