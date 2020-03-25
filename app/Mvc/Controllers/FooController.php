<?php

namespace App\Mvc\Controllers;

class FooController extends Controller
{
	public function exampleMethod($request, $response, $args)
	{
		$response->getBody()->write('Well and Good..');
		return $response;
	}

	public function oneMoreExampleMethod($request, $response, $args)
	{
		$response->getBody()->write('One more example method content');
		return $response;
	}

	public function uploadFile($request, $response, $args)
	{
		$response->getBody()->write("<form method='POST' action='/upload' enctype='multipart/form-data'><input type='file' name='file'><input type='submit' value='UPLOAD'></form>");
		return $response;
	}

	public function postFileUpload($request, $response, $args)
	{
		
		$uploadedFile = $request->getUploadedFiles();

		$file = $uploadedFile['file'];

		if( $file->getError() === UPLOAD_ERR_OK ){
			$fileName = $file->getClientFilename();
			$fileSize = $file->getSize();
			$fileType = $file->getClientMediaType();
			$file->moveTo('C:\xampp\htdocs\slim.2020.project\uploads\\' . $fileName);
		}

		return $response;
	}
}