<?php
// update_product.php
require_once __DIR__ . '/api_autoload.php';

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
 
// check for required fields
if (isset($_POST['pid']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])) {

    $pid            = $_POST['pid'];
    $name           = $_POST['name'];
    $price          = $_POST['price'];
    $description    = $_POST['description'];
    $updated_at     = date('Y-m-d H:i:s');
 

    // mysql update row with matched pid
    //$result = $con->prepare("UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE pid = $pid");
    $sql = $con->prepare("UPDATE products SET name=? , price=? , description=?, updated_at=? WHERE pid=?");    
    $sql->bind_param("ssssi",$name, $price, $description, $updated_at, $pid);    

    $result = $sql->execute();
    //////////////////////

    // check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Product successfully updated.";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
 
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>