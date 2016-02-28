<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Shortcodes;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ShortcodeTest implements Shortcode {

    public function name()
    {
        return "test-shortcode";
    }

    public function handler()
    {
        return function(ShortcodeInterface $shortcode) {
            return "hello, " . $shortcode->getParameter('name');
        };
    }

    public function listeners()
    {
        return [];
    }
}

class ShortcodesTests extends \PHPUnit_Framework_TestCase {

    private $shortcodes;

    protected $logger;

    function __construct()
    {
        $reflected = new \ReflectionClass($this);
        $this->logger = new Logger($reflected->getShortName());
        $this->logger->pushHandler(new ErrorLogHandler());
    }

    protected function setUp() {
        $this->shortcodes = new Shortcodes;
    }

    public function testIdentityShortcode() {
        $this->logger->info("testIdentityShortcode ...");
        $this->shortcodes->register(new Identity);
        $this->assertEquals("toto", $this->shortcodes->process("[identity]toto[/identity]"));
    }

    public function testVerbatimShortcode() {
        $this->logger->info("testVerbatimShortcode ...");
        $this->shortcodes->register(new Identity);
        $this->shortcodes->register(new Verbatim);
        $this->assertEquals("[identity]toto[/identity]", $this->shortcodes->process("[verbatim][identity]toto[/identity][/verbatim]"));
    }

    public function testShortCodeWithArgument() {
        $this->logger->info("testShortCodeWithArgument ...");
        $this->shortcodes->register(new ShortcodeTest);
        $this->assertEquals("hello, world", $this->shortcodes->process("[test-shortcode name=\"world\"]"));
    }

}
