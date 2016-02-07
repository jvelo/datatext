<?php

namespace Jvelo\Paidia\Shortcodes;


class Identity implements Shortcode {

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

    public function listeners()
    {
        return [];
    }
}