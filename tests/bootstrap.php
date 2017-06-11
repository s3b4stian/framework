<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
include dirname(__DIR__).'/src/Linna/Autoloader.php';

function call_autoloader()
{
    $loader = new \Linna\Autoloader();
    $loader->register();

    $loader->addNamespaces([
        //start composer packages
        ['Psr\SimpleCache', dirname(__DIR__).'/vendor/s3b4stian/simple-cache/src'],
        ['Psr\Container', dirname(__DIR__).'/vendor/psr/container/src'],
        ['MongoDB', dirname(__DIR__).'/vendor/mongodb/mongodb/src'],
        //end composer packages
        ['Linna', dirname(__DIR__).'/src/Linna'],
        ['Linna\Foo', __DIR__.'/FooClass'],
        ['Linna\Auth', dirname(__DIR__).'/src/Linna/Auth'],
        ['Linna\Cache', dirname(__DIR__).'/src/Linna/Cache'],
        ['Linna\DI', dirname(__DIR__).'/src/Linna/DI'],
        ['Linna\Storage', dirname(__DIR__).'/src/Linna/Storage'],
        ['Linna\DataMapper', dirname(__DIR__).'/src/Linna/DataMapper'],
        ['Linna\Http', dirname(__DIR__).'/src/Linna/Http'],
        ['Linna\Mvc', dirname(__DIR__).'/src/Linna/Mvc'],
        ['Linna\Session', dirname(__DIR__).'/src/Linna/Session'],
    ]);
}

call_autoloader();
