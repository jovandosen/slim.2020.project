<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\Gallery;
use App\Mvc\Models\User;
use App\Services\ValidateGalleryData;

class GalleryController extends Controller
{
	public function galleryData($request, $response)
	{
		$message = $this->container->get('flash')->getMessages();

		if( !empty($message) ){
			$message = $message['galleryCreated'][0];
		} else {
			$message = '';
		}

		$user = $request->getParsedBody();

		$galleries = User::find($user->id)->galleries;

		$galleryCount = 0;

		foreach($galleries as $k => $v){
			$galleryCount++;
		}

		if($galleryCount === 0){
			$galleries = false;
		}

		$view = $this->container->get('twig');

		echo $view->render('gallery.twig', ['user' => $user, 'message' => $message, 'galleries' => $galleries]);

		return $response;
	}

	public function postGalleryData($request, $response)
	{
		$data = $request->getParsedBody();

		$name = $data['name'];
		$description = $data['description'];
		$userID = $data['userID'];

		// validate gallery data with back end
		$validator = new ValidateGalleryData($name, $description);
		$result = $validator->validate();
		// end of back end validation

		if( $result === false ){
			// create gallery
			$gallery = new Gallery;

			$gallery->user_id = $userID;
			$gallery->name = $name;
			$gallery->description = $description;

			$gallery->save();

			$this->container->get('logger')->info('New gallery added.');
			$this->container->get('flash')->addMessage('galleryCreated', 'You have successfully created gallery.');
		}

		return $response->withHeader('Location', '/gallery');
	}
}