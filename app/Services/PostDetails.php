<?php

namespace App\Services;

class PostDetails
{
	public $postTitle;
	public $postContent;
	public $postImage;
	public $userFirstName;
	public $userLastName;
	public $postID;
	public $userID;
	public $loggedUserID;

	public function __construct($postTitle, $postContent, $postImage, $userFirstName, $userLastName, $postID, $userID, $loggedUserID)
	{
		$this->postTitle = $postTitle;
		$this->postContent = $postContent;
		$this->postImage = $postImage;
		$this->userFirstName = $userFirstName;
		$this->userLastName = $userLastName;
		$this->postID = $postID;
		$this->userID = $userID;
		$this->loggedUserID = $loggedUserID;
	}
}