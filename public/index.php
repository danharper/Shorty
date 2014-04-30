<?php

require '../vendor/autoload.php';

$app = new Yolo\Kernel;

require '../Shorty/config.php';
require '../Shorty/routes.php';
require '../Shorty/bindings.php';

try {
	$app();
}
catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
	die('Unknown Request');
}
catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
	die('Unknown Request');
}

