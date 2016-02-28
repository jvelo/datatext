<?php
/*
 * Copyright (c) 2016 Jérôme Velociter <jerome@velociter.fr>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
ini_set('error_reporting', E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Facade as Facade;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;
use Jvelo\Datatext\Cli\Cli;

$capsule = new DB;

$capsule->addConnection([
    'driver' => 'pgsql',
    'host' => 'localhost',
    'database' => 'datatext',
    'username' => 'jerome',
    'password' => '',
    'charset' => 'utf8',
    'prefix' => '',
    'schema' => 'public'
]);

$container = new Container;
$container->singleton("db", function() use ($capsule) {
    return $capsule->getDatabaseManager();
});
$container->singleton("datatext.user_provider", '\Jvelo\Datatext\ConstantAdminUserProvider');
$container->singleton("datatext.shortcodes", '\Jvelo\Datatext\Shortcodes\Shortcodes');
$container->singleton("datatext.assets_manager", '\Jvelo\Datatext\Assets\DefaultAssets');
$container->singleton("datatext.api.pages", '\Jvelo\Datatext\Api\Pages');

Facade::setFacadeApplication($container);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->bind('CreatePageAndObjectTables', '\CreatePageAndObjectTables');
$container->bind('Schema', '\Illuminate\Database\Schema\Builder');

$container->singleton('app', function() use ($container) {
    return $container;
});

$cli = new Cli();
$cli->listen();