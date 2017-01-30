<?php
// DIC configuration

$container = $app->getContainer();

date_default_timezone_set($container->get('settings')['timezone']);

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['jwt'] = function ($container) {
    return new stdClass();
};

// -----------------------------------------------------------------------------
// Database connection
// -----------------------------------------------------------------------------

$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'],
        $settings['username'], $settings['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// -----------------------------------------------------------------------------
// Controllers
// -----------------------------------------------------------------------------

$container['ExamController'] = function($c) {
    return new \App\Controllers\ExamController($c->get('Exam'), $c->get('Validation'));
};

$container['ResourcesController'] = function($c) {
    return new \App\Controllers\ResourcesController($c->get('Resources'));
};

$container['OpenDayController'] = function($c) {
    return new \App\Controllers\OpenDayController(
      $c->get('OpenDay'),
      $c->get('Paginate')
    );
};

$container['UserController'] = function($c) {
    return new \App\Controllers\UserController(
      $c->get('User')
    );
};

// -----------------------------------------------------------------------------
// Model factories
// -----------------------------------------------------------------------------

$container['Exam'] = function ($container) {
    return new App\Models\Exam($container->get('db'));
};

$container['User'] = function ($container) {
    return new App\Models\User($container->get('db'));
};

$container['Resources'] = function ($container) {
    return new App\Models\Resources($container->get('db'));
};

$container['OpenDay'] = function($container) {
  return new App\Models\OpenDay($container->get('db'));
};






// -----------------------------------------------------------------------------
// Helpers
// -----------------------------------------------------------------------------

$container['Validation'] = function ($container) {
    return new App\Helpers\Validation($container->get('Exam'));
};

$container['Paginate'] = function ($container) {
    return new App\Helpers\Paginate();
};
