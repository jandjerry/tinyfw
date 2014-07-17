<?php
use TinyFw\DB\Mysql;
define('SESSION_ROOT_URL_KEY', 'current_root_url');

define('APP_DIR', dirname( __FILE__) );
define('MONIT_DIR', APP_DIR.'/data/monit');
define('MONIT_NEW_URL_EXT', '.new_url_notif');
define('MONIT_NEW_URL_ADDED', MONIT_DIR.'/new_url_added.notif');

define('WEB_DIR', APP_DIR.'../web');
define('PUBLIC_DIR', WEB_DIR.'/public');

define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'proxy_v2');
define('DATABASE_USER', 'root');


define('PROXY_DIR', APP_DIR.'/data/proxy');
define('PROXY_EXT', '.conf');

define('PROXY_ROOT_DOMAIN', '-proxy-sg-v2.epinion.me');

define('CMD_DIR', APP_DIR.'/data/cmd');
define('CMD_EXT', '.cmd');
define('_SERVER_CACHE_DIR', '/var/www/tmp');

define('_SSL_CERT', '/etc/ssl/certs/epinion.me.cert'); // /etc/ssl/certs/epinion_me.crt
define('_SSL_KEY', '/etc/ssl/certs/epinion.me.key');  // /etc/ssl/certs/epinion_me.key

define('APACHE_CONFIG_MAIN_TPL_PATH', APP_DIR.'/apache/proxy.tpl.main.conf');
define('APACHE_CONFIG_SSL_TPL_PATH', APP_DIR.'/apache/proxy.tpl.ssl.conf');
define('APACHE_CONFIG_CACHE_TPL_PATH', APP_DIR.'/apache/proxy.tpl.cache.conf');

define('PROXY_URL_PREFIX', 'p');

$host = $_SERVER['HTTP_HOST'];
if( strpos( $host, 'epinion.me') !== false ){
    
    define('DATABASE_PASS', '');
} else {
    define('DATABASE_PASS', '123456');    
}

//db instance
$db = Mysql::instance(array(
        'host' => DATABASE_HOST,
        'user' => DATABASE_USER,
        'pass' => DATABASE_PASS,
        'database' => DATABASE_NAME
));

//$HTTP_RAW_POST_DATA //Doesn't work
/*
$post = file_get_contents('php://input');
$fp = fopen('php://input', 'rb');
stream_filter_append($fp, 'dechunk', STREAM_FILTER_READ);
$HTTP_RAW_POST_DATA = stream_get_contents($fp);
*/

//Didn't works? why? php 5.3 ?
if( isset( $HTTP_RAW_POST_DATA )){
    define('HTTP_RAW_POST_DATA', $HTTP_RAW_POST_DATA );    
}




?>