<?php

ini_set('error_reporting', E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/Database/AbstractDatabaseTest.php';
include_once __DIR__ . '/ConstantAdminUserProvider.php';
include_once __DIR__ . '/../database/migrations/2016_01_24_000000_create_page_and_object_tables.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Facade as Facade;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

$capsule = new DB;

$capsule->addConnection([
    'driver' => 'pgsql',
    'host' => 'localhost',
    'database' => 'datatext',
    'username' => 'postgres',
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
