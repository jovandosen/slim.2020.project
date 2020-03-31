<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Services\ValidatePostData;

class PostController extends Controller
{
	public function postView($request, $response)
	{
		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('post.twig', ['user' => $user]);

		return $response;
	}

	public function postData($request, $response)
	{
		$data = $request->getParsedBody();
		$files = $request->getUploadedFiles();

		$postTitle = $data['title'];
		$postContent = $data['content'];
		$userID = $data['userID'];

		$postImage = $files['image'];

		// back end validation
		$validator = new ValidatePostData($postTitle, $postContent, $postImage);

		$validationResult = $validator->validate();
		// end of back end validation

		if( $validationResult === false ){

			if( $postImage->getError() === UPLOAD_ERR_OK ){
				$fileName = $postImage->getClientFilename();
				$fileSize = $postImage->getSize();
				$fileType = $postImage->getClientMediaType();
				$postImage->moveTo('C:\xampp\htdocs\slim.2020.project\public\images\posts\\' . $fileName);
			}

			// save post

		}

		return $response;
	}
}