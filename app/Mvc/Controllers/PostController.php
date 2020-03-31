<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Mvc\Models\Post;
use App\Services\ValidatePostData;

class PostController extends Controller
{
	public function postView($request, $response)
	{
		$message = $this->container->get('flash')->getMessages();

		if( !empty($message) ){
			$message = $message['postCreated'][0];
		} else {
			$message = '';
		}

		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('post.twig', ['user' => $user, 'message' => $message]);

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
			$post = new Post;

			$post->user_id = $userID;
			$post->title = $postTitle;
			$post->content = $postContent;
			$post->image = $fileName;

			$post->save();

			$this->container->get('logger')->info('New post added.');
			$this->container->get('flash')->addMessage('postCreated', 'You have successfully created post.');

		}

		return $response->withHeader('Location', '/post');
	}
}