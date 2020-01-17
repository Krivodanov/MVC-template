<?php


namespace MVC\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Libraries
{
    public function __construct()
    {
        /*
         * Инициализация Eloquent ORM
         */
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'mvc',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
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