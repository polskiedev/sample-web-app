<?php
require_once __DIR__ . '/bd_autoload.php'; 

$tpl = new APP_TEMPLATE();
$sessMgr = new SESS_MGR();

$sessMgr->check_session(SITE_URL.'/bd_login.php');

$cAction 				= '';
$product_id 			= '';
$product_name 			= '';
$product_price 			= '';
$product_description 	= '';

if(!isset($_GET['pid'])){
	$cAction = 'Add';
}
else {
	if(is_numeric($_GET['pid'])){
		$cAction = 'Edit';
		$product_id = $_GET["pid"];
	}
}

$oData = file_get_contents(API_URL."/get_product_details.php?pid=".$product_id."&apikey=".$tpl->GetFormAPIKey());

if ($oData !== false) {
	$aData = json_decode($oData, true);
    // check for empty result
	if($aData['success'] == '1' && count($aData['product']) == 1) {
		// $cHTML .= '<div class="container">';
		$ctr = 0;
		$aProducts = $aData['product'];
		foreach ($aProducts as $key => $row) {
			$product_name 			= $row['name'];
			$product_price 			= $row['price'];
			$product_description 	= $row['description'];
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>App: <?php echo $cAction;?> Product</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<?php if(ENVIRONMENT == 'PRODUCTION') { ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<?php } else { ?>
	<link rel="stylesheet" href="<?php echo SRC_URL;?>/libs/bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SRC_URL;?>/css/style.css">
</head>
<body>
<?php $tpl->CreateNavBar(); ?>
<div class="content-wrapper">
<div class="container product-form">
<form id="frm_product" action="" method="POST" onsubmit="return false;">
<?php $tpl->CreateFormAPIField(); ?>
<input type="hidden" name="pid" id="txt_pid" value="<?php echo $product_id;?>">
<div class="row">	
	<div class="col">
		<h4 class="d-flex justify-content-between align-items-center mb-3">
			<span id="label-form-action" class="text-muted"><?php echo $cAction;?> Product</span>
		</h4>
	</div>
</div>

<div id="frmMsg"></div>

<div class="form-row product-form-control">
	<div class="col">
		<!-- <label for="txt_name">Product Name</label> -->
		<input class="form-control" name="name" id="txt_name" placeholder="Product Name" required="true" type="text" value="<?php echo $product_name;?>" autocomplete="off">
		<div class="invalid-feedback">
			Please enter Product Name.
		</div>
	</div>
</div>

<div class="form-row product-form-control">
	<div class="col">
		<!-- <label for="txt_name">Price</label> -->
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">&#8369;</span>
			</div>
			<input type="text" name="price" id="txt_price" class="form-control" aria-label="Product Price" placeholder="Price" value="<?php echo $product_price;?>" required="true" autocomplete="off">
			<!--
			<div class="input-group-append">
				<span class="input-group-text">.00</span>
			</div>
			-->
		</div>
	</div>
</div>

<div class="form-row product-form-control">
	<div class="col">
		<!-- <label for="txt_name">Product Description</label> -->
		<textarea class="form-control" name="description" id="txt_description" aria-label="Product Description" placeholder="Product Description" style="resize: none;" rows="5"><?php echo $product_description;?></textarea>
	</div>
</div>

<div class="form-row product-form-control">
	<div class="col">
		<input type="submit" name="submit" id="btnSubmit" value="Save" class="btn btn-success">
		<a class="btn btn-secondary" href="<?php echo SITE_URL;?>/bd_products.php">Back</a>
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
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/popper.js-master/dist/popper.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_URL;?>/libs/bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo SRC_URL;?>/js/script.js"></script>
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
			$url = "<?php echo API_URL;?>/create_product.php?apikey=<?php echo $tpl->GetFormAPIKey();?>";

			if($pid)
			{
				$url = "<?php echo API_URL;?>/update_product.php?apikey=<?php echo $tpl->GetFormAPIKey();?>";
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
			    		$msg += ' <a href="<?php echo API_URL;?>/bd_product.php?pid='+$response.pid+'">Update?</a>';
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