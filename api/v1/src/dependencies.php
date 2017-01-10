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
      $c->get('CreateOpenDayValidation'),
      $c->get('UpdateOpenDayValidation')
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
$container['OpenDayTime'] = function($container) {
  return new App\Models\OpenDayTime($container->get('db'));
};
$container['OpenDayTimeBreakdown'] = function($container) {
  return new App\Models\OpenDayTimeBreakdown($container->get('db'));
};
$container['OpenDayJobs'] = function($container) {
  return new App\Models\OpenDayJobs($container->get('db'));
};

$container['OpenDayAttendees'] = function($container) {
  return new App\Models\OpenDayAttendees($container->get('db'));
};




// -----------------------------------------------------------------------------
// Validation factories
// -----------------------------------------------------------------------------
$container['CreateOpenDayValidation'] = function($container) {
  return new App\Validations\CreateOpenDayValidation();
};

$container['UpdateOpenDayValidation'] = function($container) {
  return new App\Validations\UpdateOpenDayValidation();
};

// -----------------------------------------------------------------------------
// Helpers
// -----------------------------------------------------------------------------

$container['Validation'] = function ($container) {
    return new App\Helpers\Validation($container->get('Exam'));
};
