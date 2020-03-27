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
		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			return $response->withHeader('Location', '/app');
		}

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

		$_SESSION['userEmail'] = $email;

		return $response->withHeader('Location', '/app');
	}

	public function login($request, $response)
	{
		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			return $response->withHeader('Location', '/app');
		}
		
		$view = $this->container->get('twig');

		echo $view->render('login.twig');

		return $response;
	}

	public function appArea($request, $response)
	{
		$user = '';

		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			$email = $_SESSION['userEmail'];
			$user = User::where('email', $email)->first();
		} else {
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('app.twig', ['user' => $user]);

		return $response;
	}

	public function logoutUser($request, $response)
	{
		session_unset();
		session_destroy();
		return $response->withHeader('Location', '/login');
	}
}