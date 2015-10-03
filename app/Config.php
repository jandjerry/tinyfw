<?php
use TinyFw\DB\Mysql;
define('SESSION_ROOT_URL_KEY', 'current_root_url');

define('APP_DIR', dirname( __FILE__) );

define('WEB_DIR', APP_DIR.'../web');
define('PUBLIC_DIR', WEB_DIR.'/public');

define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', '3306');
define('DATABASE_NAME', 'mydatabase');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', '');
define('DATABASE_DSN', 'mysql:host='.DATABASE_HOST.';port='.DATABASE_PORT.';dbname='.DATABASE_NAME );

define('CMD_DIR', APP_DIR.'/data/cmd');
define('CMD_EXT', '.cmd');
define('_SERVER_CACHE_DIR', '/var/www/tmp');


$host = $_SERVER['HTTP_HOST'];
