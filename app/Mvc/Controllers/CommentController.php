<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\Comment;

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
}