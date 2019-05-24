<?php

$container = $app->getContainer();

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
	return $container->view->render($response, 'error_notfound.twig');
//        $response = new \Slim\Http\Response(404);
//        return $response->write("Page not found");
    };
};

$container['csrf'] = function($container) {
    $csrf = new \Slim\Csrf\Guard();
    $csrf->setPersistentTokenMode(true);
	return $csrf;
};

$container['translator'] = function ($c) {
  $translator = new Illuminate\Translation\Translator( new Illuminate\Translation\FileLoader(
  		new Illuminate\Filesystem\Filesystem(), __DIR__ . '/lang'), 'en');

  $translator->setLocale('en');
  return $translator;
};

$container['upload_directory'] = __DIR__ . '/../cache';

$container['view'] = function($container) {
	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
		'cache' => false,
	]);
	
	$basePath = rtrim(str_ireplace('index.php','', $container['request']->getUri()->getBasePath()),'/');

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container['router'],
		$basePath
	));
	
	$view->addExtension(new \Rapid\Views\CsrfExtension(
		$container['csrf']
	));

	// $view->addExtension(new \Pmt\Slim\Twig\Extension\TranslationExtension(
	// 	$container['translator']
	// ));

	return $view;
};

$container['db'] = function ($container) {
	$settings = $container->get('database');
	$capsule = new \Illuminate\Database\Capsule\Manager;
	$capsule->addConnection($settings);
	$capsule->setAsGlobal();
	$capsule->bootEloquent();

	return $capsule;
};

$app->add(new \Slim\Middleware\Session([
  'name' => 'dummy_session',
  'autorefresh' => true,
  'lifetime' => '1 hour'
]));

//$app->add(new Rapid\Auth\AuthMiddleware());

$container->register(new \Rapid\Services\Database\EloquentServiceProvider());

$container['ApiController'] = function($container) {
	return new \Rapid\Controllers\ApiController($container);
};
$container['UsuarioController'] = function($container) {
	return new \Rapid\Controllers\UsuarioController($container);
};
$container['BlocosController'] = function($container) {
	return new \Rapid\Controllers\BlocosController($container);
};
$container['UnidadesController'] = function($container) {
	return new \Rapid\Controllers\UnidadesController($container);
};
$container['MoradoresController'] = function($container) {
	return new \Rapid\Controllers\MoradoresController($container);
};
$container['VisitantesController'] = function($container) {
	return new \Rapid\Controllers\VisitantesController($container);
};
$container['VeiculosController'] = function($container) {
	return new \Rapid\Controllers\VeiculosController($container);
};
