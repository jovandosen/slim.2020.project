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

	public function register($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('register.twig');

		return $response;
	}

	public function registerData($request, $response)
	{
		echo "string";

		return $response;
	}

	public function login($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('login.twig');

		return $response;
	}
}