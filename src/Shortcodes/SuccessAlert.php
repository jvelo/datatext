<?php

namespace Jvelo\Datatext\Shortcodes;

class SuccessAlert extends AbstractShortcode {

    public function name()
    {
        return 'success';
    }

    public function handler()
    {
        return function($shortcode) {
            return '<div class="alert alert-success" role="alert">' . $shortcode->getContent() . '</div>';
        };
    }
}
