<?php
require_once __DIR__ . '/bd_autoload.php';
 
$tpl = new app_template();
$default_username = '';
$default_password = '';

if(ENVIRONMENT == "DEVELOPMENT") {
	// $default_username = 'test';
	// $default_password = 'test';
}

if(isset($_POST['sess_mgr'])) {
  $response = array("success" => 0, "message" => "Oops! Something went wrong.");
  if($_POST['sess_mgr'] == 'webapp') {
      $sessMgr = new SESS_MGR();
      $_SESSION["user_id"]  = $_POST['user_id'];
      $_SESSION["username"] = $_POST["username"];
      $_SESSION["user_type"] = $_POST["user_type"];

      $response["success"] = 1;
  }

  echo json_encode($response);
  die();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>App: Login</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<!-- Bootstrap CSS -->
	<?php if(ENVIRONMENT == 'PRODUCTION') { ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo SRC_URL;?>/libs/bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SRC_URL;?>/css/style.css">
	<link rel="stylesheet" href="<?php echo SRC_URL;?>/css/login.css">
</head>
<body>
    <div class="container">
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <div id="frmMsg" class="row"></div>
            <img id="profile-img" class="profile-img-card" src="<?php echo SRC_URL;?>/images/avatar_2x.png" default_src="<?php echo SRC_URL;?>/images/avatar_2x.png"  />
            <p id="profile-name" class="profile-name-card"></p>
            <form id="frm_login" class="form-signin" method="POST">
            	<?php $tpl->CreateFormAPIField(); ?>
                <span id="reauth-email" class="reauth-email"></span>
            	<a href="#" id="link_not_me" style="">Not you?</a>
                <input type="text" name="username" id="inputEmail" class="form-control" placeholder="Username" required autofocus autocomplete="off" value="<?php echo $default_username;?>">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required  value="<?php echo $default_password;?>">
                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="1" name="remember_me" id="remember_me" checked="checked"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form><!-- /form -->
            <a href="#" class="forgot-password">
                Forgot the password?
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->

<?php if(ENVIRONMENT == 'PRODUCTION') { ?>
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?php } else { ?>
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/popper.js-master/dist/popper.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo SRC_URL;?>/js/script.js"></script>
<script type="text/javascript" src="<?php echo SRC_URL;?>/js/login.js"></script>
<script type="text/javascript">
$( document ).ready(function() {

	loadProfile();

	var form_message = function(type, msg){
    	return '<div class="alert '+type+' alert-dismissible fade show" role="alert">'+
			   msg +
			  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
			    '<span aria-hidden="true">&times;</span>'+
			  '</button>'+
			'</div>';
    };

    $('#link_not_me').unbind('click').bind('click', function() {
    	if(supportsHTML5Storage()) { 
    		localStorage.removeItem("PROFILE_REAUTH_EMAIL");
    		localStorage.removeItem("PROFILE_NAME");
    		localStorage.removeItem("PROFILE_IMG_SRC");

	        $("#profile-img").attr("src", $("#profile-img").attr("default_src"));
	        $("#profile-name").html('');
	        $("#reauth-email").html('');
	        $("#inputEmail").val('');
	        $("#inputPassword").val('');
	        $("#inputEmail").show();
	        $("#remember").show();
	        $('#link_not_me').hide();

	        $("#inputEmail").focus();
    	}

    	
    });

	$('#frm_login').submit(function( event ) {
	  	event.preventDefault();

	  	$url = "<?php echo API_URL;?>/login.php";
	  	$formData = $( this ).serialize();
	  	$.post( $url, $formData)
  			.done(function( data ) {
  				$response = $.parseJSON(data);
  				// console.log($response);
  				if($response.success == '1') {

            $sessData = $response.user_data;
            $.post("<?php echo SITE_URL;?>/bd_login.php", $sessData).done(function( data2 ) {
                $response2 = $.parseJSON(data2);
                if($response2.success == '1') {
                  //////////////////////////////////
                  $('#frmMsg').html(form_message('alert-success', '<strong>Success!</strong> '+$response.message));
                  $('.product-list-item[data-pid="'+$('#txt_delete_pid').val()+'"]').remove();

                  if(supportsHTML5Storage() && $('#remember_me').is(':checked')) { 
                    localStorage.setItem("PROFILE_REAUTH_EMAIL", $response.login_data.PROFILE_REAUTH_EMAIL);
                    localStorage.setItem("PROFILE_NAME", $response.login_data.PROFILE_NAME);

                    if($response.login_data.PROFILE_IMG_SRC == '') {
                      localStorage.setItem("PROFILE_IMG_SRC", $("#profile-img").attr("default_src"));
                    } else {
                      localStorage.setItem("PROFILE_IMG_SRC", $response.login_data.PROFILE_IMG_SRC);
                    }

                    loadProfile();
                  }

                  setTimeout(function(){
                    if($sessData.user_type == '0') {
                      self.location = '<?php echo SITE_URL;?>/admin/index.php';
                    } else {
                      self.location = '<?php echo SITE_URL;?>/bd_main.php';
                    }
                  }, 1800);
                  //////////////////////////////////
                } else {
                  $msg = $response.message.replace("Oops!", '<strong>Oops!</strong> ');

                  $('#frmMsg').html(form_message('alert-danger', $msg));
                }
            });
  				}
  				else {
			    	$msg = $response.message.replace("Oops!", '<strong>Oops!</strong> ');

			    	$('#frmMsg').html(form_message('alert-danger', $msg));
  				}
  			});
	});

});
</script>
</body>
</html>