<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Services\ValidateRegisterData;

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
		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
			return $response->withHeader('Location', '/app');
		}

		$view = $this->container->get('twig');

		echo $view->render('login.twig');

		return $response;
	}

	public function appArea($request, $response)
	{
		$user = '';

		if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ){
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