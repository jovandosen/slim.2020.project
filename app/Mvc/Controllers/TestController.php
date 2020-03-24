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
}