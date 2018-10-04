<?php
require_once __DIR__ . '/bd_autoload.php';


$sessMgr = new SESS_MGR();
$sessMgr->destroy();

header("Location: ".SITE_URL."/bd_login.php");
?>
