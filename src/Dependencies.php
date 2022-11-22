<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$rawPost = file_get_contents('php://input');

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => (array) json_decode($rawPost),
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->share('PDO');
$injector->define('PDO', [
    ':dsn' => 'pgsql:dbname=backend;host=db',
    ':username' => 'backend',
    ':passwd' => 'backend'
]);

// Repositories
$injector->alias('OrderRepository', 'Storekeeper\AssesFullstackApi\Repositories\OrderRepository');
$injector->share('Storekeeper\AssesFullstackApi\Repositories\OrderRepository');

// Services
$injector->alias('OrderService', 'Storekeeper\AssesFullstackApi\Services\OrderService');
$injector->share('Storekeeper\AssesFullstackApi\Services\OrderService');
$injector->define('OrderService', [
    'recipeRepo' => 'OrderRepository',
]);

return $injector;
