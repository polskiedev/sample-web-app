<?php
require_once __DIR__ . '/admin_autoload.php';

$tpl = new ADMIN_APP_TEMPLATE();
$sessMgr = new SESS_MGR();

$tpl->setEnv(ENVIRONMENT);
$tpl->setSiteURL(SITE_URL);
$tpl->setResourceURL(SRC_URL);
$tpl->setActiveMainMenu('HOME');
$tpl->init();

$sessMgr->check_session(SITE_URL.'/bd_login.php');

$tpl->writeHeader();
?>


            <h2>Collapsible Sidebar Using Bootstrap 4</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>
<?php $tpl->writeFooter(); ?>
