<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Mvc\Models\User;

class AuthMiddleware
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$user = [];

		if( isset($_COOKIE['userDetails']) && !empty($_COOKIE['userDetails']) ){
			$userEmailDetail = $_COOKIE['userDetails'];
			$user = User::where("email", $userEmailDetail)->first();
		} else if( isset($_SESSION['userEmail']) && !empty($_SESSION['userEmail']) ) {	
			$userEmailDetail = $_SESSION['userEmail'];
			$user = User::where("email", $userEmailDetail)->first();
		} else {
			return $response->withHeader('Location', '/login');
		}

		$request = $request->withParsedBody($user);

		return $handler->handle($request);
	}
}