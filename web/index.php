<?php
session_start();
require_once __DIR__.'/../app/Config.php';
require_once ROOT_DIR.'/vendor/autoload.php';
require_once ROOT_DIR.'/app/App.php';

$app = new App();
$app->process();
$app->render();
