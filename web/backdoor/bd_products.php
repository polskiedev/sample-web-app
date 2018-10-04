<?php
require_once __DIR__ . '/bd_autoload.php';

$tpl = new APP_TEMPLATE();
$sessMgr = new SESS_MGR();

$sessMgr->check_session(SITE_URL.'/bd_login.php');

$products = array();
$cHTML 		= '';
$cGridRow 	= '';
$cItemRow 	= '';
$cNoProduct = '<div class="row"><strong>No Products Available.</strong></div>';

$oData = file_get_contents(API_URL."/get_all_products.php?apikey=".$tpl->GetFormAPIKey());

if ($oData !== false) {
	$aData = json_decode($oData, true);
    // check for empty result
	if($aData['success'] == '1' && count($aData['products']) > 0) {
		// $cHTML .= '<div class="container">';
		$ctr = 0;
		$aProducts = $aData['products'];
		foreach ($aProducts as $key => $row) {
			$ctr++;
			$cActions = '';
			$itemFormat = '
					  <div class="row border rounded product-list-item" data-pid="%s">
					    <div class="col-sm">
						    <h6 class="my-0">%s</h6>
						    <i><small class="text-muted product-desc"><p>%s</p></small></i>
					    </div>
					    <div class="col-sm">
					    <!--<h6 class="my-0">Price</h6>-->
					      <span class="text-muted">&#8369;%s</span>
					    </div>
					    <div class="col-sm">
					      <h6 class="my-0">Actions</h6>%s
					    </div>
					  </div>
			';



	        $product = array();
	        $product["pid"] = $row["pid"];
	        $product["name"] = $row["name"];
	        $product["price"] = $row["price"];
	        $product["description"] = $row["description"];

	        ///////////
	        $aAction = array();
	        $aAction['edit'] = '<a href="'.SITE_URL.'/bd_product.php?pid='.$product["pid"].'">Edit</a>';
	        $aAction['delete'] = '<a href="javascript:void(0);" data-pid="'.$product["pid"].'" class="delete-product">Delete</a>';
	        $cActions = implode('&nbsp;', $aAction);
	        ///////////

	        $cItemRow = sprintf($itemFormat, $product["pid"], $product['name'], $product["description"], $product["price"], $cActions);
	        
	        // $cGridRow .= '<tr>'.
	        // 					'<td>'.$product['name'].'<br><i class="pdesc">'.$product["description"].'</i></td>'.
	        // 					'<td>P'.$product['price'].'</td>'.
	        // 					'<td>'.(empty($cActions) ? '&nbsp;' : $cActions).'</td>'.
	        // 			'</tr>';
	        
	        $cHTML .= $cItemRow;
    	}
    	// $cHTML .= '</div>';
    }
    else
    {
    	// $cGridRow .= '<tr><td colspan="2">No Record Found.</td></tr>';
    	$cHTML .= $cNoProduct;
    	
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
			<span class="text-muted">Product List</span>
		</h4>
		&nbsp;&nbsp;&nbsp;<a href="<?php echo SITE_URL;?>/bd_product.php" style="line-height: 30px;">Add Product</a>
	</div>
	<div id="frmMsg" class="row"></div>
	<div id="product-list-wrapper">
	<?php echo $cHTML;?>
	</div>
</div>
<!-- 
<ul class="list-group mb-3">
<li class="list-group-item d-flex justify-content-between lh-condensed">
  <div>
    <h6 class="my-0">Product name</h6>
    <small class="text-muted">Brief description</small>
  </div>
  <div>
    <h6 class="my-0">Price</h6>
      <span class="text-muted">$12</span>
  </div>

  <div>
    <h6 class="my-0">Actions</h6>
      <a href="">Edit</a>
      <a href="">Delete</a>
  </div>
</li>
</ul>
 -->

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
			<input type="hidden" name="pid" id="txt_delete_pid">
       </form>
       <p>Are you sure you want to delete this product?</p>
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

	  	$url = "<?php echo API_URL;?>/delete_product.php";
	  	$formData = $( this ).serialize();
	  	$.post( $url, $formData)
  			.done(function( data ) {
  				$response = $.parseJSON(data);
  				// console.log($response);
  				if($response.success == '1') {
  					$('#frmMsg').html(form_message('alert-success', '<strong>Success!</strong> '+$response.message));
  					$('.product-list-item[data-pid="'+$('#txt_delete_pid').val()+'"]').remove();

  					if($('.product-list-item').length == 0) {
  						$('#product-list-wrapper').html('<?php echo $cNoProduct;?>');
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

	$('.delete-product').each(function(index, el) {
		$btnDel = this;
		$( $btnDel ).unbind('click').on('click', function(event) {
			event.preventDefault();
			
			$('#txt_delete_pid').val( $( this).data('pid') );
			$('#myDeleteModal').modal('show');
			
		});
	});
});
</script>
</body>
</html>