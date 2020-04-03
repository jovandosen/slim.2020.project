<?php

namespace App\Services;

class ValidateGalleryData
{
	protected $name;
	protected $description;

	public function __construct($name, $description)
	{
		$this->name = $name;
		$this->description = $description;
	}

	public function validate()
	{
		$error = false;
		$nameError = '';
		$descriptionError = '';

		if( empty($this->name) ){
			$error = true;
			$nameError = 'Gallery name can not be empty.';
		} else {
			$nameLength = strlen($this->name);
			if( $nameLength < 3 || $nameLength > 30 ){
				$error = true;
				$nameError = 'Gallery name must have atleast 3 characters, but not more than 30.';
			}
		}

		if( empty($this->description) ){
			$error = true;
			$descriptionError = 'Gallery description can not be empty.';
		}

		return $error;
	}
}