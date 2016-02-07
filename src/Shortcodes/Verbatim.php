<?php

namespace Jvelo\Paidia\Shortcodes;

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