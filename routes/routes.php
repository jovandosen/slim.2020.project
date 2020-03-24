<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

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
	$fooTest->test();
	return $response;
});