<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

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
