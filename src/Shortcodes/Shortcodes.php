<?php

namespace Jvelo\Datatext\Shortcodes;

use Thunder\Shortcode\EventContainer\EventContainer;
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegularParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Shortcodes
{
    private $handlers;

    private $events;

    private $processor;

    function __construct()
    {
        $this->handlers = new HandlerContainer();
        $this->events = new EventContainer();
        $this->processor = new Processor(new RegularParser(), $this->handlers);
    }

    function register(Shortcode $shortcode)
    {
        $this->handlers->add($shortcode->name(), $shortcode->handler());

        foreach ($shortcode->listeners() as $listener) {
            $this->events->addListener($listener['type'], $listener['handler']);
        }

        $this->processor = new Processor(new RegularParser(), $this->handlers);
        $this->processor = $this->processor->withEventContainer($this->events);
    }

    function process($text)
    {
        return $this->processor->process($text);
    }
}
