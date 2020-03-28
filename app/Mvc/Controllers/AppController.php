<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Services\ValidateRegisterData;
use App\Services\ValidateLoginData;

class AppController extends Controller
{
	public function home($request, $response)
	{
		$view = $this->container->get('twig');

		echo $view->render('home.twig');

		return $response;
	}

	public function register($request, $response)
	{
		if( isset($_COOKIE['userDetails']) && !empty($_COOKIE['userDetails']) ){
			return $response->withHeader('Location', '/app');
		}

		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			return $response->withHeader('Location', '/app');
		}

		$view = $this->container->get('twig');

		echo $view->render('register.twig');

		return $response;
	}

	public function registerData($request, $response)
	{
		$data = $request->getParsedBody();

		$firstName = $data['firstName'];
		$lastName = $data['lastName'];
		$email = $data['email'];
		$password = $data['password'];
		$emails = $data['userEmails'];

		$emails = explode(",", $emails);

		// back end validation

		$validator = new ValidateRegisterData($firstName, $lastName, $email, $password, $emails);

		$result = $validator->validateData();

		// end of back end validation

		if( $result === false ){
			$user = new User;

			$user->firstName = $firstName;
			$user->lastName = $lastName;
			$user->email = $email;
			$user->password = password_hash($password, PASSWORD_DEFAULT);

			$user->save();

			$_SESSION['userEmail'] = $email;

			return $response->withHeader('Location', '/app');
		}
	}

	public function login($request, $response)
	{
		if( isset($_COOKIE['userDetails']) && !empty($_COOKIE['userDetails']) ){
			return $response->withHeader('Location', '/app');
		}

		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			return $response->withHeader('Location', '/app');
		}

		$view = $this->container->get('twig');

		echo $view->render('login.twig');

		return $response;
	}

	public function loginData($request, $response)
	{
		$data = $request->getParsedBody();

		$email = $data['email'];
		$password = $data['password'];
		$emails = $data['userEmails'];

		if( isset($data['saveUserData']) ){
			$rememberMe = $data['saveUserData'];
		} else {
			$rememberMe = 'off';
		}

		$emails = explode(",", $emails);

		// back end validation
		$validator = new ValidateLoginData($email, $password, $emails);

		$result = $validator->validateData();

		if( $result === false ){
			$user = User::where("email", $email)->first();
			$checkPassword = password_verify($password, $user->password);
			if( $checkPassword ){
				$_SESSION['userEmail'] = $user->email;
				if( $rememberMe == "on" ){
					setcookie("userDetails", $user->email, time()+3600);
				}
				return $response->withHeader('Location', '/app');
			} else {
				$passwordWrong = 'wrong';
				$emailOk = $user->email;
				$view = $this->container->get('twig');
				echo $view->render('login.twig', ['passwordWrong' => $passwordWrong, 'emailOk' => $emailOk]);
			}
		}

		return $response;
	}

	public function appArea($request, $response)
	{
		$user = '';

		if( isset($_COOKIE['userDetails']) && !empty($_COOKIE['userDetails']) ){
			$email = $_COOKIE['userDetails'];
			$user = User::where('email', $email)->first();
		} else if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			$email = $_SESSION['userEmail'];
			$user = User::where('email', $email)->first();
		} else {
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('app.twig', ['user' => $user]);

		return $response;
	}

	public function logoutUser($request, $response)
	{
		session_unset();
		session_destroy();
		if( isset($_COOKIE['userDetails']) ){
			setcookie("userDetails", "", time()-3600);
		}
		return $response->withHeader('Location', '/login');
	}

	public function getEmails($request, $response)
	{
		$connection = new \mysqli('localhost', 'root', '', 'slim2020');

		if( $connection->connect_error ){
			die("Error while connecting to database:" . $connection->connect_error);
		}

		$sql = "SELECT email FROM users";

		$records = $connection->query($sql);

		$emails = [];

		if( $records->num_rows > 0 ){
			while( $row = mysqli_fetch_object($records) ){
				$emails[] = $row->email;
			}
		}

		$emails = json_encode($emails);

		$records->close();

		$connection->close();

		echo $emails;

		return $response;
	}
}