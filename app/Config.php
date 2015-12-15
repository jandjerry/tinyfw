<?php
use TinyFw\DB\Mysql;
define('SESSION_ROOT_URL_KEY', 'current_root_url');

define('APP_DIR', dirname( __FILE__) );
define('ROOT_DIR', realpath(APP_DIR.'/../'));

define('WEB_DIR', ROOT_DIR.'/web');
define('PUBLIC_DIR', WEB_DIR.'/public');


define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', '3306');
define('DATABASE_NAME', 'mydatabase');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', '');
define('DATABASE_DSN', 'mysql:host='.DATABASE_HOST.';port='.DATABASE_PORT.';dbname='.DATABASE_NAME );
