<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

session_start();

$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true
  ]
]);

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
    ->withHeader('Access-Control-Allow-Origin', getenv('APP_URL'))
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$container = $app->getContainer();

$container['db'] = function() {
  return new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
};

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
	    'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['flash'] = function() {
    return new \Slim\Flash\Messages();
};

require __DIR__ . '/../routes/web.php';

?>
