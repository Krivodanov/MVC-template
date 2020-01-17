<?php


namespace MVC\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use MVC\App\Config;

class Libraries
{
    public function __construct()
    {
        /*
         * Инициализация Eloquent ORM
         */
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'    => Config::DB_DRIVER,
            'host'      => Config::DB_HOST,
            'database'  => Config::DB_DATABASE,
            'username'  => Config::DB_USERNAME,
            'password'  => Config::DB_PASSWORD,
            'charset'   => Config::DB_CHARSET,
            'collation' => Config::DB_COLLATION,
            'prefix'    => Config::DB_PREFIX,
        ]);
        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        /*
         * Инициализация шаблонизатора Twig
         */
        $cache = '..\view\compilation_cache';
        $cache = false; //Отключение кеширования шаблонизатора
        $loader = new FilesystemLoader(__DIR__ . '/View');
        $twig = new Environment($loader, array(
            'cache' => $cache,
        ));
        Response::$twig = $twig;
    }
}