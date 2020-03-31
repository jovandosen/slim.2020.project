<?php
session_start();
//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Services\Foo;
use App\Mvc\Controllers\TestController;
use Slim\Routing\RouteCollectorProxy;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__ . '/../vendor/autoload.php';

$container = new \DI\Container();

AppFactory::setContainer($container);

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'slim2020',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->set('Capsule', function() use ($capsule){
	return $capsule;
});

$container->set('twig', function(){

	$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/Mvc/Views');
	$twig = new \Twig\Environment($loader, ['cache' => false, 'debug' => true]);

	$twig->addGlobal('bazz', 'global variable');

	$function = new \Twig\TwigFunction('function_name', function () {
    	echo "THIS IS TWIG CUSTOM FUNCTION";
	});
	$twig->addFunction($function);

	$twig->addExtension(new \Twig\Extension\DebugExtension());

	return $twig;
});

$container->set('flash', function(){
    return new \Slim\Flash\Messages();
});

$container->set('logger', function(){
    $logger = new Logger('Logger');
    $fileHandler = new StreamHandler(__DIR__ . '/../logs/app.log');
    $logger->pushHandler($fileHandler);
    return $logger;
});

$container->set('fooTest', function(){
	return new Foo();
});

$container->set('TestController', function($container){
	return new TestController($container);
});

$container->set('FooController', function($container){
	return new \App\Mvc\Controllers\FooController($container);
});

$container->set('BarController', function($container){
	return new \App\Mvc\Controllers\BarController($container);
});

$container->set('AppController', function($container){
	return new \App\Mvc\Controllers\AppController($container);
});

$container->set('PostController', function($container){
    return new \App\Mvc\Controllers\PostController($container);
});

$app = AppFactory::create();

$app->addRoutingMiddleware();

$beforeMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);
    $existingContent = (string) $response->getBody();

    $response = new Response();
    $response->getBody()->write('BEFORE' . $existingContent);

    return $response;
};

$afterMiddleware = function ($request, $handler) {
    $response = $handler->handle($request);
    $response->getBody()->write('AFTER');
    return $response;
};

require __DIR__ . '/../routes/routes.php';

//$app->add($beforeMiddleware);
//$app->add($afterMiddleware);

$app->addErrorMiddleware(true, true, true);

$app->run();