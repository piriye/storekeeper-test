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
$injector->alias('ItemRepository', 'Storekeeper\AssesFullstackApi\Repositories\ItemRepository');
$injector->share('Storekeeper\AssesFullstackApi\Repositories\ItemRepository');

$injector->alias('OrderRepository', 'Storekeeper\AssesFullstackApi\Repositories\OrderRepository');
$injector->share('Storekeeper\AssesFullstackApi\Repositories\OrderRepository');

$injector->alias('OrderItemRepository', 'Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository');
$injector->share('Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository');

$injector->alias('UserRepository', 'Storekeeper\AssesFullstackApi\Repositories\UserRepository');
$injector->share('Storekeeper\AssesFullstackApi\Repositories\UserRepository');

// Services
$injector->alias('OrderService', 'Storekeeper\AssesFullstackApi\Services\OrderService');
$injector->share('Storekeeper\AssesFullstackApi\Services\OrderService');
$injector->define('OrderService', [
    'authService' => 'AuthService',
    'itemRepo' => 'ItemRepository',
    'orderItemRepo' => 'OrderItemRepository',
    'orderRepo' => 'OrderRepository',
]);

$injector->alias('AuthService', 'Storekeeper\AssesFullstackApi\Services\AuthService');
$injector->share('Storekeeper\AssesFullstackApi\Services\AuthService');
$injector->define('AuthService', [
    'userRepo' => 'UserRepository',
]);

return $injector;
