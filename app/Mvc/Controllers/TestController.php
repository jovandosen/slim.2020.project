<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;

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

	public function twigTest($request, $response)
	{
		$twig = $this->container->get('twig');

		echo $twig->render('test.twig', ['foo' => 'foo123']);

		return $response;
	}

	public function getUsers($request, $response)
	{
		$users = User::all();

		$twig = $this->container->get('twig');

		echo $twig->render('users.twig', ['users' => $users]);

		return $response;
	}

	public function createUser($request, $response)
	{
		$user = User::create([
			'name' => 'Jelena',
			'email' => 'jelena@gmail.com',
			'password' => 'jelena'
		]);

		return $response;
	}
}