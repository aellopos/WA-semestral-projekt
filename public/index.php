<?php

use DI\Bridge\Slim\Bridge;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// Load env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// Init app
$container = new Container();
$app = Bridge::create($container);

// Dependencies
$container->set('db', fn() => new PDO("sqlite:../database/database.sqlite"));

// Middleware
$middleware = include __DIR__ . '/../src/middleware.php';
$middleware($app);

// Routes
$routes = include __DIR__ . '/../src/routes.php';
$routes($app);

// Start!
$app->run();