<?php

namespace App\Mvc\Controllers;

class CommentController extends Controller
{
	public function addComment($request, $response)
	{
		$data = $request->getParsedBody();

		$comment = $data['comment'];
		$postID = $data['postID'];
		$userID = $data['userID'];

		return $response;
	}
}