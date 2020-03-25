<?php

namespace App\Mvc\Controllers;

class BarController extends Controller
{
	public function __invoke($request, $response, $args)
	{
		$response->getBody()->write('eee');
		return $response;
	}
}