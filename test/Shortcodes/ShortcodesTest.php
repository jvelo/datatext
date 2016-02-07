<?php

namespace Jvelo\Paidia\Shortcodes;

use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

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


}