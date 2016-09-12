<?php

include dirname(__DIR__).'/src/Linna/Autoloader.php';

//var_dump(dirname(__DIR__).'/src/Linna/Autoloader.php');

$loader = new \Linna\Autoloader();
$loader->register();

$loader->addNamespaces([
    ['Linna', dirname(__DIR__) . '/src/Linna'],
    ['Linna\Auth', dirname(__DIR__) . '/src/Linna/Auth'],
    ['Linna\DI', dirname(__DIR__) . '/src/Linna/DI'],
    ['Linna\Database', dirname(__DIR__) . '/src/Linna/Database'],
    ['Linna\Http', dirname(__DIR__) . '/src/Linna/Http'],
    ['Linna\Mvc', dirname(__DIR__) . '/srcLinna/Mvc'],
    ['Linna\Session', dirname(__DIR__) . '/srcLinna/Session'],
]);
