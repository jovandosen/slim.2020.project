<?php

namespace App\Services;

class ValidatePostUpdateData
{
	protected $postTitle;
	protected $postContent;
	protected $postImage;
	protected $postImageOld;

	public function __construct($postTitle, $postContent, $postImage, $postImageOld)
	{
		$this->postTitle = $postTitle;
		$this->postContent = $postContent;
		$this->postImage = $postImage;
		$this->postImageOld = $postImageOld;
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

		if( empty($this->postImage) && empty($this->postImageOld) ){
			$error = true;
			$postImageError = 'Please add post image.';
		}

		return $error;
	}
}