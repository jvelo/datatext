<?php

namespace Jvelo\Datatext\Shortcodes;

interface Shortcode {

    public function name();

    public function handler();

    public function listeners();
}
