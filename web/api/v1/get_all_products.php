<?php
// get_all_products.php
require_once __DIR__ . '/api_autoload.php';
 
// connecting to db
$db = new DB_CONNECT();
$con = $db->conn();
 
/*
 * Following code will list all the products
 */
 
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

// array for JSON response
$response = array();

// get all products from products table
// $result = mysql_query("SELECT *FROM products") or die(mysql_error());
$sql = "SELECT * FROM products";
$result = $con->query($sql);

// check for empty result
// if (mysql_num_rows($result) > 0) {
if ($result->num_rows > 0) {
    // looping through all results
    // products node
    $response["products"] = array();
 
    // while ($row = mysql_fetch_array($result)) {
    while ($row = $result->fetch_assoc()) {
        // temp user array
        $item = array();
        $item["pid"] = $row["pid"];
        $item["name"] = $row["name"];
        $item["price"] = $row["price"];
        $item["description"] = $row["description"];
        $item["created_at"] = $row["created_at"];
        $item["updated_at"] = $row["updated_at"];
 
        // push single item into final response array
        array_push($response["products"], $item);
    }
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No products found";
 
    // echo no users JSON
    echo json_encode($response);
}
?>