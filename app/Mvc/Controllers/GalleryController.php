<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\Gallery;
use App\Mvc\Models\Image;
use App\Mvc\Models\User;
use App\Services\ValidateGalleryData;

class GalleryController extends Controller
{
	public function galleryData($request, $response)
	{
		$message = $this->container->get('flash')->getMessages();

		if( !empty($message) && !empty($message['galleryCreated'][0]) ){
			$message = $message['galleryCreated'][0];
		} else if( !empty($message['imagesUploaded'][0]) ) {
			$message = $message['imagesUploaded'][0];
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

	public function uploadGalleryData($request, $response)
	{
		$data = $request->getParsedBody();
		$files = $request->getUploadedFiles();

		$userID = $data['userDataID'];
		$galleryID = $data['galleryID'];

		foreach($files as $key => $images){
			foreach($images as $k => $image){

				$imgName = $image->getClientFilename();
				$imgSize = $image->getSize();
				$imgType = $image->getClientMediaType();
				$image->moveTo('C:\xampp\htdocs\slim.2020.project\public\images\gallery\\' . $imgName);

				$img = new Image;

				$img->user_id = $userID;
				$img->gallery_id = $galleryID;
				$img->name = $imgName;

				$img->save();
			}
		}

		$this->container->get('flash')->addMessage('imagesUploaded', 'You have successfully uploaded images.');

		return $response->withHeader('Location', '/gallery');
	}

	public function getGalleryImages($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){
			// ajax call for images
			if( isset($_GET['id']) ){
				$id = $_GET['id'];
				$images = Gallery::find($id)->images;
				$images = json_encode($images, JSON_PRETTY_PRINT);
				echo $images;
				return $response;
			}
			//
		}
	}

	public function deleteGalleryImage($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){
			// ajax call for image delete
			if( isset($_GET['imgID']) ){
				$imgID = $_GET['imgID'];
				$image = Image::find($imgID);
				$image->delete();
				return $response;
			}
			//
		}
	}
}