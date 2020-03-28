<?php

namespace App\Services;

class ValidateLoginData
{
	protected $email;
	protected $password;
	protected $emails;

	public function __construct($email, $password, $emails)
	{
		$this->email = $email;
		$this->password = $password;
		$this->emails = $emails;
	}

	public function validateData()
	{
		$error = false;

		$emailError = '';
		$passwordError = '';

		if( empty($this->email) ){
			$error = true;
			$emailError = 'Email field can not be empty.';
		} elseif( $this->validateEmailAddress($this->email) === false ){
			$error = true;
			$emailError = 'Email address is not valid.';
		} else {
			// check email exists
			$emailCount = 0;

			foreach ($this->emails as $key => $value) {
				if( $this->email == $value ){
					$emailCount++;
				}
			}

			if( $emailCount === 0 ){
				$error = true;
				$emailError = 'Email address does not exist.';
			}
		}

		if( empty($this->password) ){
			$error = true;
			$passwordError = 'Password field can not be empty.';
		} else {
			if( strlen($this->password) < 5 || strlen($this->password) > 20 ){
				$error = true;
				$passwordError = 'Password must have atleast 5 characters, but not more than 20.';
			}
		}

		return $error;

	}

	public function validateEmailAddress($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
  			return false;
		} else {
			return true;
		}
	}
}