<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/app_template.php';
require_once __DIR__ . '/sess_mgr.php';
require_once __DIR__ . '/common.php'; 
 

$tpl = new APP_TEMPLATE();
$sessMgr = new SESS_MGR();
$db = new DB_CONNECT();

if((isset($_SESSION['initiated']) && isset($_SESSION['user_id']) && isset($_SESSION['username'])) == false) {
	header("Location: ". SITE_URL.'/bd_login.php');
}

$con = $db->conn();


$cAction 			= '';
$user_id 			= '';

if(!isset($_GET['user_id'])){
   echo "Required parameter(s) is missing";
   die();
}
else {
	if(is_numeric($_GET['user_id'])){
		$cAction = 'Edit';
		$user_id = $_GET["user_id"];
	}
}


// $oData = file_get_contents(SITE_URL."/get_product_details.php?pid=".$user_id."&apikey=".$tpl->GetFormAPIKey());

// if ($oData !== false) {
// 	$aData = json_decode($oData, true);
//     // check for empty result
// 	if($aData['success'] == '1' && count($aData['product']) == 1) {
// 		// $cHTML .= '<div class="container">';
// 		$ctr = 0;
// 		$aProducts = $aData['product'];
// 		foreach ($aProducts as $key => $row) {
// 			$product_name 			= $row['name'];
// 			$product_price 			= $row['price'];
// 			$product_description 	= $row['description'];
// 		}
// 	}
// }
?>
<!DOCTYPE html>
<html>
<head>
	<title>App: <?php echo $cAction;?> User Profile Picture</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<?php if(ENVIRONMENT == 'PRODUCTION') { ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo SITE_URL;?>/src/libs/bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SITE_URL;?>/src/style.css">
</head>
<body>
<?php $tpl->CreateNavBar(); ?>
<div class="content-wrapper">
<div class="container product-form">
<form id="frm_upload" action="<?php echo SITE_URL.'/upload_image.php';?>" method="POST" enctype="multipart/form-data"><!--#onsubmit="return false;"-->
<?php $tpl->CreateFormAPIField(); ?>
<input type="hidden" name="user_id" id="txt_user_id" value="<?php echo $user_id;?>">
<div class="row">	
	<div class="col">
		<h4 class="d-flex justify-content-between align-items-center mb-3">
			<span id="label-form-action" class="text-muted"><?php echo $cAction;?> User Profile Picture</span>
		</h4>
	</div>
</div>

<div id="frmMsg"></div>

<div class="form-row product-form-control">
	<div class="col">
		Select image to upload:<br>
		<!-- <label for="txt_name">Product Name</label> -->
		<input type="file" name="file_upload" id="file_upload" required="true">

	</div>
</div>

<div class="form-row product-form-control">
	<div class="col">
		<input type="submit" name="submit" id="btnSubmit" value="Upload" class="btn btn-success">
		<a class="btn btn-secondary" href="<?php echo SITE_URL;?>/bd_users.php">Back</a>
	</div>
</div>
</form>
</div>

<!--@Modal-->
<div id="myModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to save data?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-modal-save">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<?php if(ENVIRONMENT == 'PRODUCTION') { ?>
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?php } else { ?>
<script type="text/javascript" src="<?php echo SITE_URL;?>/src/libs/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>/src/libs/popper.js-master/dist/popper.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>/src/libs/bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo SITE_URL;?>/src/script.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    console.log( "ready!" );

// $('#exampleModal').show();

    // $('.alert').alert('close');
    // $('#btnSubmit').unbind('click').click(function(){
    // });
    
    var form_message = function(type, msg){
    	return '<div class="alert '+type+' alert-dismissible fade show" role="alert">'+
			   msg +
			  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
			    '<span aria-hidden="true">&times;</span>'+
			  '</button>'+
			'</div>';
    };

	$( "#frm_product" ).on( "submit", function( event ) {
	  	event.preventDefault();

		$('#frmMsg').html('');
		$('#myModal').modal('show');

		$formData = $( this ).serialize();
		// console.log($formData);
		
		$('#btn-modal-save').on('click', function(){
			$pid = $('#txt_pid').val();
			$url = "<?php echo SITE_URL;?>/create_product.php?apikey=<?php echo $tpl->GetFormAPIKey();?>";

			if($pid)
			{
				$url = "<?php echo SITE_URL;?>/update_product.php?apikey=<?php echo $tpl->GetFormAPIKey();?>";
			}	
			// console.log($url);

			$.post( $url, $formData)
			  .done(function( data ) {
			    // alert( "Data Loaded: " + data );
			    $response = $.parseJSON(data);

			    // console.log($response);
			    if($response.success == '1') {
			    	$('#frmMsg').html(form_message('alert-success', '<strong>Success!</strong> '+$response.message));
					if($response.pid && !$pid)
					{
						$cFrmAction = 'Edit Product';
						$('#txt_pid').val($response.pid);
						$('#label-form-action').html($cFrmAction);
						document.title = 'App: ' + $cFrmAction;
					}
					//Oops! 
			    } else {
			    	$msg = $response.message.replace("Oops!", '<strong>Oops!</strong> ');
			    	
			    	if($response.error_code == 'ALREADY_EXISTS'){
			    		$msg += ' <a href="<?php echo SITE_URL;?>/bd_product.php?pid='+$response.pid+'">Update?</a>';
			    	}

			    	$('#frmMsg').html(form_message('alert-danger', $msg));
			    }

			    $('#myModal').modal('hide');
			  });
		});


	});
    
});
</script>
</body>
</html>