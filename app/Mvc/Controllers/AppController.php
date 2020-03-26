<?php

namespace App\Mvc\Controllers;

class AppController extends Controller
{
	public function home($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('home.twig');

		return $response;
	}
}