<?php

namespace Jvelo\Paidia\Shortcodes;

interface Shortcode {

    public function name();

    public function handler();

    public function listeners();
}