<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;

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
		$data = $request->getParsedBody();

		$firstName = $data['firstName'];
		$lastName = $data['lastName'];
		$email = $data['email'];
		$password = $data['password'];

		$user = new User;

		$user->firstName = $firstName;
		$user->lastName = $lastName;
		$user->email = $email;
		$user->password = password_hash($password, PASSWORD_DEFAULT);

		$user->save();

		return $response->withHeader('Location', '/app');
	}

	public function login($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('login.twig');

		return $response;
	}

	public function appArea($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('app.twig');

		return $response;
	}
}