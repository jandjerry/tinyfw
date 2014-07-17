<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../App/Config.php';

use App\App;
$app = new App();
$app->process();
$app->render();

?>