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

	public function __construct($postTitle, $postContent, $postImage, $userFirstName, $userLastName, $postID, $userID)
	{
		$this->postTitle = $postTitle;
		$this->postContent = $postContent;
		$this->postImage = $postImage;
		$this->userFirstName = $userFirstName;
		$this->userLastName = $userLastName;
		$this->postID = $postID;
		$this->userID = $userID;
	}
}