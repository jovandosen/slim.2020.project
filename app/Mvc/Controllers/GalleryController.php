<?php

namespace App\Mvc\Controllers;

class GalleryController extends Controller
{
	public function galleryData($request, $response)
	{
		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('gallery.twig', ['user' => $user]);

		return $response;
	}
}