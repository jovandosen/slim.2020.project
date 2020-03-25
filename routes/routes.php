<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', function(Request $request, Response $response, $args){
	$response->getBody()->write('Hello world.');
	return $response;
});

$app->get('/test', function(Request $request, Response $response, $args){
	$response->getBody()->write('Test route');
	return $response;
});

$app->get('/foo/{name}', function(Request $request, Response $response, $args){
	$name = $args['name'];
	$response->getBody()->write($name);
	return $response;
});

$app->get('/json/example', function(Request $request, Response $response, $args){
	$jsonExample = json_encode(['name' => 'Testing'], JSON_PRETTY_PRINT);
	$response->getBody()->write($jsonExample);
	return $response->withHeader('Content-Type', 'application-json');
});

$app->get('/testing', function(Request $request, Response $response, $args){
	$fooTest = $this->get('fooTest');
	$response->getBody()->write($fooTest->test());
	return $response;
});

$app->get('/bar', function(Request $request, Response $response, $args){
	return $response->withHeader('Location', '/testing')->withStatus(302);
});

$app->get('/dev', 'TestController:someData')->setName('dev.route');

$app->group('/group', function(RouteCollectorProxy $group){

	$group->get('/one', 'TestController:one')->setName('one');

	$group->get('/two', 'TestController:two')->setName('two');

});

$app->get('/baz', 'FooController:exampleMethod')->setName('baz');

$app->get('/bazz', \FooController::class . ':oneMoreExampleMethod');

$app->get('/bazzz', \BarController::class); // using invoke magic method

$app->get('/upload', 'FooController:uploadFile')->setName('file.upload');

$app->post('/upload', 'FooController:postFileUpload');

$app->get('/twig', 'TestController:twigTest')->setName('twig.route');

$app->get('/users', 'TestController:getUsers')->setName('users');

$app->get('/user', 'TestController:createUser');