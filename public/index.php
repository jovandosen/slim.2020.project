<?php
session_start();
//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Services\Foo;
use App\Mvc\Controllers\TestController;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$container = new \DI\Container();

AppFactory::setContainer($container);

$container->set('fooTest', function(){
	return new Foo();
});

$container->set('TestController', function($container){
	return new TestController($container);
});

$container->set('FooController', function($container){
	return new \App\Mvc\Controllers\FooController($container);
});

$container->set('BarController', function($container){
	return new \App\Mvc\Controllers\BarController($container);
});

$app = AppFactory::create();

$app->addRoutingMiddleware();

$beforeMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);
    $existingContent = (string) $response->getBody();

    $response = new Response();
    $response->getBody()->write('BEFORE' . $existingContent);

    return $response;
};

$afterMiddleware = function ($request, $handler) {
    $response = $handler->handle($request);
    $response->getBody()->write('AFTER');
    return $response;
};

require __DIR__ . '/../routes/routes.php';

//$app->add($beforeMiddleware);
//$app->add($afterMiddleware);

$app->addErrorMiddleware(true, true, true);

$app->run();