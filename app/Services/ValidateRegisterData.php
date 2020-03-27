<?php

namespace App\Services;

class ValidateRegisterData
{
	protected $firstName;
	protected $lastName;
	protected $email;
	protected $password;
	protected $emails;

	public function __construct($firstName, $lastName, $email, $password, $emails)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->password = $password;
		$this->emails = $emails;
	}

	public function validateData()
	{
		$error = false;

		$firstNameError = '';
		$lastNameError = '';
		$emailError = '';
		$passwordError = '';

		if( empty($this->firstName) ){
			$error = true;
			$firstNameError = 'First Name field can not be empty.';
		} else {
			if( strlen($this->firstName) < 3 || strlen($this->firstName) > 20 ){
				$error = true;
				$firstNameError = 'First Name must have atleast 3 characters, but not more than 20.';
			}
		}

		if( empty($this->lastName) ){
			$error = true;
			$lastNameError = 'Last Name field can not be empty.';
		} else {
			if( strlen($this->lastName) < 3 || strlen($this->lastName) > 20 ){
				$error = true;
				$lastNameError = 'Last Name must have atleast 3 characters, but not more than 20.';
			}
		}

		if( empty($this->email) ){
			$error = true;
			$emailError = 'Email field can not be empty.';
		} elseif( $this->validateEmailAddress($this->email) === false ){
			$error = true;
			$emailError = 'Email address is not valid.';
		} else {
			// check email exists
			foreach ($this->emails as $key => $value) {
				if( $this->email == $value ){
					$error = true;
					$emailError = 'Email address already exists.';
				}
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