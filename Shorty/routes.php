<?php

use Shorty\Controller\CreateTagController;
use Shorty\Controller\HomeController;
use Shorty\Controller\TagRedirectController;

$app->post('/', CreateTagController::class);
$app->get('/', HomeController::class);
$app->get('/{tag}', TagRedirectController::class);
