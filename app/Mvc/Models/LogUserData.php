<?php

namespace App\Mvc\Models;

class LogUserData
{
	public $userID;
	public $postID;
	public $editTime;

	public function __construct($userID, $postID, $editTime)
	{
		$this->userID = $userID;
		$this->postID = $postID;
		$this->editTime = $editTime;
	}
}