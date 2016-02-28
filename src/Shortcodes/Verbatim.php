<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Shortcodes;

use Thunder\Shortcode\Events;
use Thunder\Shortcode\Event\FilterShortcodesEvent;

class Verbatim implements Shortcode {

    public function name()
    {
        return "verbatim";
    }

    public function handler()
    {
        return function($shortcode) {
            return $shortcode->getContent();
        };
    }

    public function listeners()
    {
        return [
            [
                "type" => Events::FILTER_SHORTCODES,
                "handler" => function(FilterShortcodesEvent $event) {
                    $parent = $event->getParent();
                    if ($parent && ($parent->getName() === 'verbatim' || $parent->hasAncestor('verbatim'))) {
                        $event->setShortcodes(array());
                    }
                }
            ]
        ];
    }
}
