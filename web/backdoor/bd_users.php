<?php
// include db connect class
require_once __DIR__ . '/bd_autoload.php';
 

$tpl = new APP_TEMPLATE();
$sessMgr = new SESS_MGR();

$sessMgr->check_session(SITE_URL.'/bd_login.php');

$products = array();
$cHTML 		= '';
$cGridRow 	= '';
$cItemRow 	= '';
$cNoUser = '<div class="row"><strong>No Users Available.</strong></div>';

$oData = file_get_contents(API_URL."/get_all_users.php?apikey=".$tpl->GetFormAPIKey());

if ($oData !== false) {
	$aData = json_decode($oData, true);
    // check for empty result
	if($aData['success'] == '1' && count($aData['users']) > 0) {
		// $cHTML .= '<div class="container">';
		$ctr = 0;
		$aProducts = $aData['users'];
		foreach ($aProducts as $key => $row) {
			$ctr++;
			$cActions = '';
			$itemFormat = '
					  <div class="row border rounded user-list-item" data-user-id="%s">
					    <div class="col-sm">
						    <h6 class="my-0">%s</h6>
						    <i><small class="text-muted">%s</small></i>
					    </div>
					    <div class="col-sm">
					      <h6 class="my-0">Actions</h6>%s
					    </div>
					  </div>
			';



	        $item = array();
	        $item["user_id"] = $row["user_id"];
	        $item["name"] = $row["name"];
	        $item["username"] = $row["username"];

	        ///////////
	        $aAction = array();
	        $aAction['edit'] = '<a href="'.SITE_URL.'/bd_user.php?user_id='.$item["user_id"].'">Edit</a>';
	        $aAction['delete'] = '<a href="javascript:void(0);" data-user-id="'.$item["user_id"].'" class="delete-item">Delete</a>';
	        $cActions = implode('&nbsp;', $aAction);
	        ///////////
	        $lblUsername = $item['username'];

	        if($row['user_type'] == '0') {
	        	$lblUsername .= ' (Administrator)';
	        }
	        ///////////

	        $cItemRow = sprintf($itemFormat, $item["user_id"], $item['name'], $lblUsername, $cActions);
	       
	        $cHTML .= $cItemRow;
    	}
    }
    else
    {
    	$cHTML .= $cNoUser;
    	
    }	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>App: Product List</title>
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
</head>
<body>
<?php $tpl->CreateNavBar(); ?>
<div class="content-wrapper">
<!-- 
<h4>Product List</h4>
<table class="table table-hover">
<thead>
<tr>
	<th>Product Name</th>
	<th>Price</th>
	<th>Actions</th>
</tr>	
</thead>
<?php echo $cGridRow; ?>
</table> -->
<div class="container">
	<div class="row">	
		<h4 class="d-flex justify-content-between align-items-center mb-3">
			<span class="text-muted">User List</span>
		</h4>
		&nbsp;&nbsp;&nbsp;<a href="<?php echo SITE_URL;?>/bd_user.php" style="line-height: 30px;">Add User</a>
	</div>
	<div id="frmMsg" class="row"></div>
	<div id="user-list-wrapper">
	<?php echo $cHTML;?>
	</div>
</div>

</div>
<!--@Modal-->
<div id="myDeleteModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form id="frm_delete_product" action="" method="POST" onsubmit="return false;">
       		<?php $tpl->CreateFormAPIField(); ?>
			<input type="hidden" name="user_id" id="txt_delete_user_id">
       </form>
       <p>Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-modal-delete">Yes</button>
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
	var form_message = function(type, msg){
    	return '<div class="alert '+type+' alert-dismissible fade show" role="alert">'+
			   msg +
			  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
			    '<span aria-hidden="true">&times;</span>'+
			  '</button>'+
			'</div>';
    };

	$('#frm_delete_product').submit(function( event ) {
	  	event.preventDefault();

	  	$url = "<?php echo API_URL;?>/delete_user.php";
	  	$formData = $( this ).serialize();
	  	$.post( $url, $formData)
  			.done(function( data ) {
  				$response = $.parseJSON(data);
  				// console.log($response);
  				if($response.success == '1') {
  					$('#frmMsg').html(form_message('alert-success', '<strong>Success!</strong> '+$response.message));
  					$('.user-list-item[data-user-id="'+$('#txt_delete_user_id').val()+'"]').remove();

  					if($('.user-list-item').length == 0) {
  						$('#user-list-wrapper').html('<?php echo $cNoUser;?>');
  					}
  				}
  				else {
			    	$msg = $response.message.replace("Oops!", '<strong>Oops!</strong> ');

			    	$('#frmMsg').html(form_message('alert-danger', $msg));
  				}
  				
  				$('#myDeleteModal').modal('hide');
  			});
	});


	$('#btn-modal-delete').unbind('click').on('click', function(){
		$('#frm_delete_product').submit();
	});

	$('.delete-item').each(function(index, el) {
		$btnDel = this;
		$( $btnDel ).unbind('click').on('click', function(event) {
			event.preventDefault();
			$('#txt_delete_user_id').val( $( this).data('user-id') );
			$('#myDeleteModal').modal('show');
			
		});
	});
});
</script>
</body>
</html>