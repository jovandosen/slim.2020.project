<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;

class PostController extends Controller
{
	public function postView($request, $response)
	{
		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('post.twig', ['user' => $user]);

		return $response;
	}
}