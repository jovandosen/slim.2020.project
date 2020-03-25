<?php

namespace App\Mvc\Controllers;

class FooController extends Controller
{
	public function exampleMethod($request, $response, $args)
	{
		$response->getBody()->write('Well and Good..');
		return $response;
	}

	public function oneMoreExampleMethod($request, $response, $args)
	{
		$response->getBody()->write('One more example method content');
		return $response;
	}
}