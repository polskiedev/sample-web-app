<?php
// update_product.php
require_once __DIR__ . '/config.php';
// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/common.php';

// connecting to db
$db = new DB_CONNECT();
$con = $db->conn();

if (!isset($_POST['apikey'])) {
    $response = array();
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required parameter(s) is missing";

    // echoing JSON response
    echo json_encode($response);
    die();
} else {
    $apikey = $_POST['apikey'];
    $oCheckApiKey = $db->validate_api_key($apikey);

    if($oCheckApiKey == false) {
        $response["success"] = 0;
        $response["message"] = "Invalid API key";   

        // echoing JSON response
        echo json_encode($response);
        die();
    } else {
        define("SESSMGR", $oCheckApiKey);
    }
}
/*
 * Following code will update a product information
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
$field_name = 'file_upload';
$upload_dir = './src/images/users/';
// check for required fields
if (is_uploaded_file($_FILES[$field_name]['tmp_name']) && isset($_POST['user_id'])) {
	$id = $_POST['user_id'];
	$tmp_file = $_FILES[$field_name]['tmp_name'];
	$image_name = $_FILES[$field_name]['name'];
	$ext = pathinfo($image_name, PATHINFO_EXTENSION);

	$filename = $id.'.'.$ext;
	$image_url = SITE_URL.'/src/images/users/'.$id.'.'.$ext;

//////////////////////////////////////////////////////////
    $check = @getimagesize($_FILES[$field_name]["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        // $uploadOk = 1;
    } else {
       	$response["success"] = 0;
	    $response["message"] = "File is not an image.";
	 
	    // echoing JSON response
	    echo json_encode($response);
    	die();
    }


	// // Check if file already exists
	// if (file_exists($target_file)) {
	//     echo "Sorry, file already exists.";
	//     $uploadOk = 0;
	// } 
	  
	// Check file size
	// if ($_FILES[$field_name]["size"] > 500000) {
	//     echo "Sorry, your file is too large.";
	//     $uploadOk = 0;
	// } 
	
	// Allow certain file formats
	if($ext != "jpg" && $ext != "png" && $ext != "jpeg" && $ext != "gif" ) {
       	$response["success"] = 0;
	    $response["message"] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	 
	    // echoing JSON response
	    echo json_encode($response);
    	die();
	}
//////////////////////////////////////////////////////////

    $sql = $con->prepare("UPDATE users SET profile_picture=? WHERE user_id=?");    
    $sql->bind_param("si", $image_url, $id);
    $result = $sql->execute();
    
    // check if row updated or not
    if ($result && move_uploaded_file($tmp_file, $upload_dir.$filename)) {

        // successfully updated
        $response["success"] = 1;
        // $response["message"] = "User successfully updated.";
        $response["message"] = "The file ". basename($_FILES[$field_name]["name"]). " has been uploaded.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
	    $response["success"] = 0;
	    $response["message"] = "Sorry, there was an error uploading your file.";
	 
	    // echoing JSON response
	    echo json_encode($response);
    }

}
?>