<?php

namespace App\Mvc\Controllers;

class TestController extends Controller
{
	public function someData($request, $response)
	{
		$x = $this->container->get('fooTest');

		$response->getBody()->write($x->test());

		return $response;
	}

	public function one($request, $response)
	{
		$response->getBody()->write('one');
		return $response;
	}

	public function two($request, $response)
	{
		$response->getBody()->write('two');
		return $response;
	}
}