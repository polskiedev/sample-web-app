<?php 
class ADMIN_APP_TEMPLATE extends APP_TEMPLATE{
	// var $env = 'PRODUCTION';
	private $ENV = 'DEVELOPMENT';
	private $SITE_URL = '';
	private $SRC_URL = '';
	private $ACTIVE_MAIN_MENU = '';
	private $LIST_MAIN_MENU = array();

	function __construct(){
		
	}

	function init() {
		$this->setMainMenu(array(
        	'HOME' => array('label' => 'Home', 'link' => $this->getSiteURL()),
        	'PRODUCTS' => array('label' => 'Products', 'link' => ''),
        	'USER_ACCOUNTS' => array('label' => 'User Accounts', 'link' => ''),
    	));
	}

	function nl() {
		return "\r\n";
	}

	function setEnv($env = '') {
		$this->ENV = $env;
	}

	function getEnv() {
		return $this->ENV;
	}

	function setResourceURL($url = '') {
		$this->SRC_URL = $url;
	}

	function getResourceURL() {
		return $this->SRC_URL;
	}

	function setSiteURL($url = '') {
		$this->SITE_URL = $url;
	}

	function getSiteURL() {
		return $this->SITE_URL;
	}

	function setActiveMainMenu($main_menu = '') {
		$this->ACTIVE_MAIN_MENU = $main_menu;
	}

	function setMainMenu($aMenu = array()) {
		$this->LIST_MAIN_MENU = $aMenu;
	}

	function getHeader() {

		$cHTML = '';

		$cHTML .= '<!DOCTYPE html>
			<html>
			<head>
				<title>Web App</title>
				<!-- Required meta tags -->
			    <meta charset="utf-8">
			    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
				<!-- Bootstrap CSS -->';

				if($this->getEnv() == 'PRODUCTION') {
					$cHTML .= $this->nl().'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">';
				} else {
					$cHTML .= $this->nl().'<link rel="stylesheet" href="'.$this->getResourceURL().'/libs/bootstrap-4.1.3-dist/css/bootstrap.min.css">';	
				}

				$cHTML .= $this->nl().'<link rel="stylesheet" href="'.$this->getResourceURL().'/css/admin_style.css">';	
				//$this->ACTIVE_MAIN_MENU
				$cHTML .= $this->nl().'</head>
								<body>
								    <div class="wrapper">
								        <!-- Sidebar  -->
								        <nav id="sidebar">
								            <div class="sidebar-header">
								                <h3>Web App Admin</h3>
								            </div>
								            <ul class="list-unstyled components">';
				if(count($this->LIST_MAIN_MENU) > 0) {
					foreach ($this->LIST_MAIN_MENU as $mKey => $menuItem) {
						$cHTML .= '<li class="'.($mKey == $this->ACTIVE_MAIN_MENU ? 'active' : '').'">'.
										'<a href="'.(empty($menuItem['link']) ? '#' : $menuItem['link']).'">'.$menuItem['label'].'</a></li>';
					}
				}

								                // <li class="active">
								                //     <a href="#">Home</a>
								                // </li>
								                // <!-- <li><a href="#">Orders</a></li> -->
								                // <li><a href="#">Products</a></li>
								                // <!-- <li><a href="#">Customers</a></li>
								                // <li><a href="#">Analytics</a></li>
								                // <li><a href="#">Apps</a></li> -->
								                // <li><a href="#">User Accounts</a></li>
								                // <!-- <li><a href="#">Settings</a></li> -->

				$cHTML .= $this->nl().'
								            </ul>
								        </nav>

								        <!-- Page Content  -->
								        <div id="content">

								            <nav class="navbar navbar-expand-lg navbar-light bg-light">
								                <div class="container-fluid">

								                    <button type="button" id="sidebarCollapse" class="btn btn-info">
								                        <i class="fas fa-align-left"></i>
								                        <span>Toggle Sidebar</span>
								                    </button>
								                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								                        <i class="fas fa-align-justify"></i>
								                    </button>

								                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
								                        <ul class="nav navbar-nav ml-auto">
								                            <!--<li class="nav-item active">
								                                <a class="nav-link" href="#">Page</a>
								                            </li>
								                            <li class="nav-item">
								                                <a class="nav-link" href="#">Page</a>
								                            </li>
								                            <li class="nav-item">
								                                <a class="nav-link" href="#">Account</a>
								                            </li>-->
								                            <li class="nav-item">
								                                <a class="nav-link" href="'.str_replace("/admin", "", $this->getResourceURL()).'/bd_logout.php">Logout</a>
								                            </li>
								                        </ul>
								                    </div>
								                </div>
								            </nav>';

		return $cHTML;
	}

	function getFooter(){
		$cHTML = '';
		$cHTML .= '
		        </div>
		    </div>';

		if($this->getEnv() == 'PRODUCTION') { 
			$cHTML .= '
				<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
				<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>';
		} else {
			$cHTML .= '
				<script type="text/javascript" src="'.$this->getResourceURL().'/libs/jquery-3.3.1.min.js"></script>
				<script type="text/javascript" src="'.$this->getResourceURL().'/libs/popper.js-master/dist/popper.min.js"></script>
				<script type="text/javascript" src="'.$this->getResourceURL().'/libs/bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
			';

		}

		$cHTML .= '
				<!-- Font Awesome JS -->
				<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
				<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
				<script type="text/javascript" src="'.$this->getResourceURL().'/js/script.js"></script>
				<script type="text/javascript">
				$( document ).ready(function() {
				    $("#sidebarCollapse").on("click", function () {
				        $("#sidebar").toggleClass("active");
				    });
				});
				</script>
				</body>
				</html>';

		return $cHTML;
	}

	function writeHeader() {
		echo $this->getHeader();
	}

	function writeFooter() {
		echo $this->getFooter();
	}
}
?>