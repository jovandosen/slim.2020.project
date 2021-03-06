<?php

namespace App\Mvc\Controllers;

use App\Mvc\Models\User;
use App\Services\ValidateRegisterData;
use App\Services\ValidateLoginData;
use App\Services\ValidateProfileData;

class AppController extends Controller
{
	public function home($request, $response)
	{
		$user = $request->getParsedBody();

		$view = $this->container->get('twig');

		echo $view->render('home.twig', ['user' => $user]);

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

			$this->container->get('logger')->info('New user registered.');

			$this->container->get('flash')->addMessage('Registered', 'You have successfully registered.');

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
		$message = $this->container->get('flash')->getMessages();

		if( !empty($message) && isset($message['Registered']) ){
			$message = $message['Registered'][0];
		} else if( isset($message['Updated']) ) {
			$message = $message['Updated'][0];
		} else {
			$message = '';
		}

		$user = $request->getParsedBody();

		if( empty($user) ){
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('app.twig', ['user' => $user, 'message' => $message]);

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

	public function profile($request, $response)
	{
		$user = $request->getParsedBody();

		if( empty($user) ){
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('profile.twig', ['user' => $user]);

		return $response;
	}

	public function profileData($request, $response)
	{
		$data = $request->getParsedBody();

		$firstName = $data['firstName'];
		$lastName = $data['lastName'];
		$email = $data['email'];
		$emails = $data['userEmails'];
		$oldEmail = $data['oldUserEmail'];

		$emails = explode(",", $emails);

		// validation
		$validator = new ValidateProfileData($firstName, $lastName, $email, $oldEmail, $emails);

		$result = $validator->validateData();

		if( $result === false ){

			$user = User::where('email', $oldEmail)->first();

			$user->firstName = $firstName;
			$user->lastName = $lastName;
			$user->email = $email;

			$user->save();

			$_SESSION['userEmail'] = $email;

			$this->container->get('flash')->addMessage('Updated', 'You have successfully updated data.');

			return $response->withHeader('Location', '/app');

		}

		return $response;
	}

	public function getPassword($request, $response)
	{
		$user = $request->getParsedBody();

		if( empty($user) ){
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('password.twig', ['user' => $user]);

		return $response;
	}

	public function changePasswordData($request, $response)
	{
		$data = $request->getParsedBody();

		$passwordOld = $data['passwordOld'];
		$passwordNew = $data['passwordNew'];

		$user = User::where("email", $_SESSION['userEmail'])->first();

		if($user){
			if( password_verify($passwordOld, $user->password) ){
				$user->password = password_hash($passwordNew, PASSWORD_DEFAULT);
				$user->save();
				$successMessage = 'Password successfully changed.';
				$view = $this->container->get('twig');
				echo $view->render('password.twig', ['successMessage' => $successMessage, 'user' => $user]);
			} else {
				$errorMess = 'Wrong password, try again.';
				$view = $this->container->get('twig');
				echo $view->render('password.twig', ['errorMessage' => $errorMess, 'user' => $user]);
			}
		}

		return $response;
	}

	public function picture($request, $response)
	{
		$user = $request->getParsedBody();

		if( empty($user) ){
			return $response->withHeader('Location', '/login');
		}

		$view = $this->container->get('twig');

		echo $view->render('picture.twig', ['user' => $user]);

		return $response;
	}

	public function pictureData($request, $response)
	{
		$uploadedFile = $request->getUploadedFiles();

		$file = $uploadedFile['picture'];

		if( $file->getError() === UPLOAD_ERR_OK ){
			$fileName = $file->getClientFilename();
			$fileSize = $file->getSize();
			$fileType = $file->getClientMediaType();
			$file->moveTo('C:\xampp\htdocs\slim.2020.project\public\images\pictures\\' . $fileName);

			$details = $request->getParsedBody();
			$userID = $details['userID'];

			$user = User::where("id", $userID)->first();
			$user->image = $fileName;
			$user->save();

		}

		return $response->withHeader('Location', '/picture');
	}
}