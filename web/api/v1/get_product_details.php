<?php
// get_product_details.php
require_once __DIR__ . '/api_autoload.php';
 
// connecting to db
$db = new DB_CONNECT();
$con = $db->conn();

if (!isset($_GET['apikey'])) {
    $response = array();
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required parameter(s) is missing";

    // echoing JSON response
    echo json_encode($response);
    die();
} else {
    $apikey = $_GET['apikey'];
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
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
 
// check for post data
if (isset($_GET["pid"])) {
    $pid = $_GET['pid'];
 
    // get a product from products table
    // $result = mysql_query("SELECT *FROM products WHERE pid = $pid");
 
    $sql = $con->prepare("SELECT * FROM products WHERE pid=?");
    $sql->bind_param("i",$pid);          
    $sql->execute();
    $result = $sql->get_result();

    if (!empty($result)) {
        // check for empty result
        // if (mysql_num_rows($result) > 0) {
        if ($result->num_rows > 0) {
 
            // $result = mysql_fetch_array($result);
            $result = $result->fetch_assoc();
 
            $product = array();
            $product["pid"] = $result["pid"];
            $product["name"] = $result["name"];
            $product["price"] = $result["price"];
            $product["description"] = $result["description"];
            $product["created_at"] = $result["created_at"];
            $product["updated_at"] = $result["updated_at"];
            // success
            $response["success"] = 1;
 
            // user node
            $response["product"] = array();
 
            array_push($response["product"], $product);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No product found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No product found";
 
        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>