<?php
require_once  '../../../vendor/autoload.php';

$ENV_FILE = '.env.autoload';

if(file_exists( __DIR__ . '/env/'.$ENV_FILE))
{
	$dotenv = new Dotenv\Dotenv( __DIR__ . '/env', $ENV_FILE);
	$dotenv->load();
}

if(strpos( $_SERVER['HTTP_HOST'], getenv('APP_PROD_API_DOMAIN')) !== false){
    define('SITE_URL', getenv('APP_PROD_API_URL'));
    define('ENVIRONMENT', "PRODUCTION");

}
else {
	define('SITE_URL', getenv('APP_DEV_API_URL'));
	define('ENVIRONMENT', "DEVELOPMENT");
}
?>