<?php

namespace App\Services;

class PostDetails
{
	public $postTitle;
	public $postContent;
	public $postImage;
	public $userFirstName;
	public $userLastName;

	public function __construct($postTitle, $postContent, $postImage, $userFirstName, $userLastName)
	{
		$this->postTitle = $postTitle;
		$this->postContent = $postContent;
		$this->postImage = $postImage;
		$this->userFirstName = $userFirstName;
		$this->userLastName = $userLastName;
	}

	public function getPostDetails()
	{
		//
	}
}