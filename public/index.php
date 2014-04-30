<?php

use Yolo\Kernel;
use Shorty\Controller\CreateTagController;
use Shorty\Controller\HomeController;
use Shorty\Controller\TagRedirectController;

require '../vendor/autoload.php';

$app = new Kernel;

$app['config.template_path'] = __DIR__.'/../web';
$app['config.mysql.host'] = 'localhost';
$app['config.mysql.user'] = 'root';
$app['config.mysql.password'] = '';
$app['config.mysql.database'] = 'shorty';

$app->post('/', CreateTagController::class);
$app->get('/', HomeController::class);
$app->get('/{tag}', TagRedirectController::class);

$app->bind('Yolo\PdoFactory', 'Yolo\MySqlPdoFactory');

try {
	$app();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

