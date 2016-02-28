<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Jvelo\Datatext\Database;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Facade as Facade;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

abstract class AbstractDatabaseTest extends \PHPUnit_Framework_TestCase {

    protected $logger;

    protected $schema;

    function __construct()
    {
        $reflected = new \ReflectionClass($this);
        $this->logger = new Logger($reflected->getShortName());
        $this->logger->pushHandler(new ErrorLogHandler());
    }

    protected function setUp()
    {
        parent::setUp();
        $this->schema = DB::schema();

        $migration = App::make('CreatePageAndObjectTables');
        $migration->down();
        $migration->up();
    }

}
