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
// PHP Mailer
// -----------------------------------------------------------------------------

$container['phpmailer'] = function ($c) {
    $settings = $c->get('settings')['phpmailer'];

    $phpmailer = new PHPMailer;

    $phpmailer->SMTPDebug   = $settings['smtp_debug'];
    $phpmailer->isSMTP();
    $phpmailer->Host        = $settings['host'];
    $phpmailer->SMTPAuth    = $settings['smtp_auth'];
    $phpmailer->Username    = $settings['username'];
    $phpmailer->Password    = $settings['password'];
    $phpmailer->SMTPSecure  = $settings['smtp_secure'];
    $phpmailer->Port        = $settings['port'];

    $phpmailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $phpmailer->setFrom($settings['sender_email'], $settings['sender_name']);

    return $phpmailer;
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
      $c->get('User'),
      $c->get('Paginate'),
      $c->get('EmailHelper'),
      $c->get('SocketNotifier'),
      $c->get('Notification')
    );
};

$container['UserController'] = function($c) {
    return new \App\Controllers\UserController(
      $c->get('User')
    );
};

$container['PromotionController'] = function($c) {
    return new \App\Controllers\PromotionController(
      $c->get('Promotion'),
      $c->get('User')


    );
};

$container['NotificationController'] = function($c) {
    return new \App\Controllers\NotificationController(
      $c->get('Notification'),
      $c->get('Paginate'),
      $c->get('SocketNotifier')
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

$container['Notification'] = function($container) {
  return new App\Models\Notification($container->get('db'));
};

$container['Promotion'] = function($container) {
  return new App\Models\Promotion($container->get('db'));
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

$container['SocketNotifier'] = function ($container) {
    return new App\Helpers\SocketNotifier();
};

$container['EmailHelper'] = function ($container) {
    return new App\Helpers\EmailHelper($container->get('phpmailer'));
};

