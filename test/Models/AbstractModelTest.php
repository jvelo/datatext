<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Facade as Facade;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

abstract class AbstractModelTest extends \PHPUnit_Framework_TestCase {

    protected $logger;

    protected $schema;

    function __construct()
    {
        $reflected = new \ReflectionClass($this);
        $this->logger = new Logger($reflected->getShortName());
        $this->logger->pushHandler(new ErrorLogHandler());
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $capsule = new DB;

        $capsule->addConnection([
            'driver' => 'pgsql',
            'host' => 'localhost',
            'database' => 'datoum',
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
        $container->singleton("paidia.user_provider", '\Jvelo\Paidia\ConstantAdminUserProvider');

        Facade::setFacadeApplication($container);

        $capsule->setEventDispatcher(new Dispatcher($container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $container->bind('CreatePageAndObjectTables', '\CreatePageAndObjectTables');
        $container->bind('Schema', '\Illuminate\Database\Schema\Builder');

        $container->singleton('app', function() use ($container) {
            return $container;
        });
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