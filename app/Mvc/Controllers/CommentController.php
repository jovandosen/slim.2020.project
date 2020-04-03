<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\Comment;
use App\Mvc\Models\Post;
use App\Mvc\Models\User;
use App\Services\CommentDetails;

class CommentController extends Controller
{
	public function addComment($request, $response)
	{
		$data = $request->getParsedBody();

		$comment = $data['comment'];
		$postID = $data['postID'];
		$userID = $data['userID'];

		$newComment = new Comment;

		$newComment->post_id = $postID;
		$newComment->user_id = $userID;
		$newComment->comment = $comment;

		$newComment->save();

		return $response->withHeader('Location', '/blog');
	}

	public function getComments($request, $response)
	{
		$ajaxCall = $request->getHeader('X-Requested-With');

		if( $ajaxCall[0] === 'XMLHttpRequest' ){

			$postID = $_GET['postID'];

			$comments = Post::find($postID)->comments;

			$data = [];

			foreach($comments as $key => $comment){

				$userComment = $comment->comment;
				$userCommentCreated = $comment->created_at->diffForHumans();

				$userID = $comment->user_id;

				$userData = User::find($userID);

				$userFirstName = $userData->firstName;
				$userLastName = $userData->lastName;

				$commentDetails = new CommentDetails($userFirstName, $userLastName, $userComment, $userCommentCreated);

				$data[] = $commentDetails;
			}

			$data = json_encode($data, JSON_PRETTY_PRINT);

			echo $data;

			return $response;
		}
	}
}