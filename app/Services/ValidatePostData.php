<?php

namespace App\Services;

class ValidatePostData
{
	protected $postTitle;
	protected $postContent;
	protected $postImage;

	public function __construct($postTitle, $postContent, $postImage)
	{
		$this->postTitle = $postTitle;
		$this->postContent = $postContent;
		$this->postImage = $postImage;
	}

	public function validate()
	{
		$error = false;
		$postTitleError = '';
		$postContentError = '';
		$postImageError = '';

		if( empty($this->postTitle) ){
			$error = true;
			$postTitleError = 'Post Title can not be empty.';
		} else {
			if( strlen($this->postTitle) < 3 || strlen($this->postTitle) > 30 ){
				$error = true;
				$postTitleError = 'Post Title must have atleast 3 characters, but not more than 30.';
			}
		}

		if( empty($this->postContent) ){
			$error = true;
			$postContentError = 'Post Content can not be empty.';
		} else {
			if( strlen($this->postContent) < 3 ){
				$error = true;
				$postContentError = 'Post Content must have atleast 3 characters.';
			}
		}

		if( empty($this->postImage) ){
			$error = true;
			$postImageError = 'Please add post image.';
		}

		return $error;
	}
}