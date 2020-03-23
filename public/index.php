<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new \DI\Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->get('/', function(Request $request, Response $response, $args){
	$response->getBody()->write('Hello world.');
	return $response;
});

$app->get('/test', function(Request $request, Response $response, $args){
	$response->getBody()->write('Test route');
	return $response;
});

$app->addErrorMiddleware(true, true, true);

$app->run();