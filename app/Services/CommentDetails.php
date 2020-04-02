<?php

namespace App\Services;

class CommentDetails
{
	public $userFirstName;
	public $userLastName;
	public $userComment;
	public $userCommentCreated;

	public function __construct($userFirstName, $userLastName, $userComment, $userCommentCreated)
	{
		$this->userFirstName = $userFirstName;
		$this->userLastName = $userLastName;
		$this->userComment = $userComment;
		$this->userCommentCreated = $userCommentCreated;
	}
}