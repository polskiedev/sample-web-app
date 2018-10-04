<?php
require_once  '../../../vendor/autoload.php';

$ENV_FILE = '.env.autoload';
$dotenv = new Dotenv\Dotenv(__DIR__ .'/env', $ENV_FILE);
$dotenv->load();

if(strpos( $_SERVER['HTTP_HOST'], getenv('APP_PROD_DOMAIN')) !== false){
    define('SITE_URL', getenv('APP_PROD_URL'));
    define('ENVIRONMENT', "PRODUCTION");

}
else {
	define('SITE_URL', getenv('APP_DEV_URL'));
	define('ENVIRONMENT', "DEVELOPMENT");
}
?>