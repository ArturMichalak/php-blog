<?php

namespace App;
use FastRoute;
use FastRoute\RouteCollector;
use BlogPage\Controllers\ArticleController;
use Libs\GoogleAuth\GoogleAuth;

$container = require __DIR__ . '/../app/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/logout', [GoogleAuth::class, 'logout']);
    $r->addRoute('GET', '/{id}', [ArticleController::class, 'item']);
    $r->addRoute('GET', '/', [ArticleController::class, 'list']);
});

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        // We could do $container->get($controller) but $container->call()
        // does that automatically
        $container->call($controller, $parameters);
        break;
}