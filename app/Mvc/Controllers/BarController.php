<?php

namespace App\Mvc\Controllers;

class BarController extends Controller
{
	public function __invoke($request, $response, $args)
	{
		$response->getBody()->write('eee');
		$response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
		$response = $response->withHeader('test', 'yes');
		return $response;
	}
}