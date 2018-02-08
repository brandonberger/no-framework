<?php declare(strict_types = 1);
// Namespace for src directory
namespace Underground;
// Autoloader
require __DIR__ . '/../vendor/autoload.php';
// Reports all error types
error_reporting(E_ALL);

$environment = 'development';

/**
 * Register the error handler
 * @package "filp/whoops": "~2.1"
 * Error handler, displays an error page
 * and additional debugging info
 */
// Initializes the whoops packages class
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e) {
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();


/**
 * Dependency Injector
 * @package "rdlowrey/auryn":"~1.4.2"
 * to bootstrap and wire together S.O.L.I.D.,
 * object-oriented PHP applications.
 */
$injector = include('Dependencies.php');

$request  = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

// Router callback
/**
 * @package "nikic/fast-route":"~1.2"
 * dispatches to different handlers
 * depending on rules that you have set up
 */
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $class = $injector->make($className);
        $class->$method($vars);
        break;
}



foreach ($response->getHeaders() as $header) {
    header($header, false);
}

echo $response->getContent();
