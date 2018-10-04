<?php
 
/*
 * All database connection variables
 */
$ENV_FILE = '.env.testing';
if(ENVIRONMENT == "PRODUCTION"){
	$ENV_FILE = '.env.production';
}

$dotenv = new Dotenv\Dotenv(__DIR__.'/env', $ENV_FILE);
// $dotenv->required(['DB_USER', 'DB_PASSWORD', 'DB_DATABASE', 'DB_SERVER', 'DB_PORT', 'DB_SOCKET']);
$dotenv->load();

define('DB_USER', getenv('DB_USER')); // db user
define('DB_PASSWORD', getenv('DB_PASSWORD')); // db password (mention your db password here)
define('DB_DATABASE', getenv('DB_DATABASE')); // database name
define('DB_SERVER', getenv('DB_SERVER')); // db server
define('DB_PORT', getenv('DB_PORT')); // db port
define('DB_SOCKET', getenv('DB_SOCKET')); // db socket
?>