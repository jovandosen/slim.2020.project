<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Mvc\Models\Post;
use App\Services\ValidatePostData;
use App\Services\ValidatePostUpdateData;
use App\Services\PostDetails;

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

		$userID = $user->id;

		$postID = $_GET['postId'];

		$post = Post::find($postID);

		// check log data

		$connection = new \mysqli('localhost', 'root', '', 'slim2020');

		if( $connection->connect_error ){
			die('Error while connecting to database:' . $connection->connect_error);
		}

		$sql = "SELECT * FROM logs WHERE post_id=?";

		$record = $connection->prepare($sql);

		$record->bind_param('i', $postID);
		$record->execute();

		$data = $record->get_result();

		$currentTime = date('Y-m-d H:i:s');

		if($data->num_rows > 0){
			while( $row = mysqli_fetch_object($data) ){
				// compare time
				$diff = strtotime($currentTime) - strtotime($row->editTime);
				if( $diff < 60 && $row->user_id != $userID ){
					echo 'You cant edit now.';

					$userEdit = User::find($row->user_id);

					$view = $this->container->get('twig');

					echo $view->render('edit-post.twig', ['user' => $user, 'post' => $post, 'userEdit' => $userEdit]);

					return $response;

				} else {

					// update row

					$connectionTwo = new \mysqli('localhost', 'root', '', 'slim2020');

					if( $connectionTwo->connect_errno ){
						echo "Failed to connect to MySQL: (" . $connectionTwo->connect_errno . ") " . $connectionTwo->connect_error;
						die();
					}

					$sqlTwo = "UPDATE logs SET editTime=? WHERE post_id=?";

					$connTwo = $connectionTwo->prepare($sqlTwo);

					$connTwo->bind_param("si", $currentTime, $postID);
					$connTwo->execute();

					$connTwo->close();
					$connectionTwo->close();

				} 
			}
		}

		//

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

	public function getPostData($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){
			
			$postID = $_GET['postID'];

			$userID = $_GET['userID'];

			$postData = Post::find($postID);

			$postTitle = $postData->title;
			$postContent = $postData->content;
			$postImage = $postData->image;

			$userId = $postData->user_id;

			$userData = User::find($userId);

			$userFirstName = $userData->firstName;
			$userLastName = $userData->lastName;

			$details = new PostDetails($postTitle, $postContent, $postImage, $userFirstName, $userLastName, $postID, $userId, $userID);

			$details = json_encode($details, JSON_PRETTY_PRINT);

			echo $details;
		}

		return $response;
	}

	public function allPostsData($request, $response)
	{
		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('posts-data.twig', ['user' => $user]);

		return $response;
	}

	public function getPosts($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){
			$posts = Post::all();
			$posts = json_encode($posts, JSON_PRETTY_PRINT);
			echo $posts;
			return $response;
		}
	}

	public function loggUserData($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){
			
			$uID = $_GET['uID'];
			$pID = $_GET['pID'];

			$editTime = date('Y-m-d H:i:s');

			//////////////////////////////////////////////////////////////////////////////////////

			$connectionOne = new \mysqli('localhost', 'root', '', 'slim2020');

			if( $connectionOne->connect_errno ){
				echo "Failed to connect to MySQL: (" . $connectionOne->connect_errno . ") " . $connectionOne->connect_error;
				die();
			}

			$sqlOne = "SELECT * FROM logs WHERE post_id=?";

			$connOne = $connectionOne->prepare($sqlOne);

			$connOne->bind_param("i", $pID);
			$connOne->execute();

			$data = $connOne->get_result();

			if( $data->num_rows > 0 ){

				while( $row = mysqli_fetch_object($data) ){
					$currentTime = date('Y-m-d H:i:s');
					$diff = strtotime($currentTime) - strtotime($row->editTime);
					if( $diff < 60 && $uID == $row->user_id ){

						$connection = new \mysqli('localhost', 'root', '', 'slim2020');

						if( $connection->connect_errno ){
							echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
							die();
						}

						$sql = "UPDATE logs SET editTime=? WHERE post_id=?";

						$conn = $connection->prepare($sql);

						$conn->bind_param("si", $editTime, $pID);
						$conn->execute();

						$conn->close();
						$connection->close();

					}
				}

			} else {

				$connection = new \mysqli('localhost', 'root', '', 'slim2020');

				if( $connection->connect_errno ){
					echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
					die();
				}

				$sql = "INSERT INTO logs(user_id, post_id, editTime) VALUES(?, ?, ?)";

				$conn = $connection->prepare($sql);

				$conn->bind_param("iis", $uID, $pID, $editTime);
				$conn->execute();

				$conn->close();
				$connection->close();

			}

			$connOne->close();
			$connectionOne->close();

			//////////////////////////////////////////////////////////////////////////////////////

			return $response;
		}
	}
}