<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Mvc\Models\Post;
use App\Services\ValidatePostData;
use App\Services\ValidatePostUpdateData;

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

	public function getAllPosts($request, $response)
	{
		$message = $this->container->get('flash')->getMessages();

		if( !empty($message) && !empty($message['postDeleted'][0]) ){
			$message = $message['postDeleted'][0];
		} else if( !empty($message['postUpdated'][0]) ) {
			$message = $message['postUpdated'][0];
		} else {
			$message = '';
		}

		$user = $request->getParsedBody();

		$posts = User::find($user->id)->posts;

		$view = $this->container->get('twig');

		echo $view->render('posts.twig', ['user' => $user, 'posts' => $posts, 'message' => $message]);

		return $response;
	}

	public function deletePost($request, $response, $args)
	{
		$postID = $args['id'];

		$post = Post::find($postID);

		$post->delete();

		$this->container->get('logger')->info('Post deleted.');
		$this->container->get('flash')->addMessage('postDeleted', 'You have successfully deleted post.');

		return $response->withHeader('Location', '/posts');
	}

	public function updatePost($request, $response, $args)
	{
		$user = $request->getParsedBody();

		$postID = $_GET['postId'];

		$post = Post::find($postID);

		$view = $this->container->get('twig');

		echo $view->render('edit-post.twig', ['user' => $user, 'post' => $post]);

		return $response;
	}

	public function updatePostData($request, $response)
	{
		$data = $request->getParsedBody();
		$files = $request->getUploadedFiles();

		$postTitle = $data['title'];
		$postContent = $data['content'];
		$userID = $data['userID'];
		$postImageOld = $data['postImageData'];
		$postId = $data['postIdDetail'];

		$postImage = $files['image'];

		$validator = new ValidatePostUpdateData($postTitle, $postContent, $postImage, $postImageOld);

		$result = $validator->validate();

		if( $result === false ){
			
			if( $postImage->getError() === UPLOAD_ERR_OK ){
				$fileName = $postImage->getClientFilename();
				$fileSize = $postImage->getSize();
				$fileType = $postImage->getClientMediaType();
				$postImage->moveTo('C:\xampp\htdocs\slim.2020.project\public\images\posts\\' . $fileName);
			}

			$post = Post::find($postId);

			$post->user_id = $userID;
			$post->title = $postTitle;
			$post->content = $postContent;

			if( isset($fileName) && !empty($fileName) ){
				$post->image = $fileName;
			}

			$post->save();

			$this->container->get('logger')->info('Post updated.');
			$this->container->get('flash')->addMessage('postUpdated', 'You have successfully updated post.');

		}

		return $response->withHeader('Location', '/posts');
	}

	public function showBlog($request, $response)
	{
		$user = $request->getParsedBody();

		$posts = Post::all();

		$view = $this->container->get('twig');

		echo $view->render('blog.twig', ['user' => $user, 'posts' => $posts]);

		return $response;
	}
}