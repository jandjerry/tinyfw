<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../app/Config.php';
require_once '../app/App.php';

$app = new App();
$app->connectDatabase();
$app->process();
$app->render();

?>