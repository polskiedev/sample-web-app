<?php
// delete_user.php
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
 * Following code will delete a product from table
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
 
    // mysql update row with matched pid
    // $result = mysql_query("DELETE FROM products WHERE pid = $pid");

    $user_type = 0;
    $sqlCheck = $con->prepare("SELECT * FROM users WHERE user_id != ? and user_type = ?");
    $sqlCheck->bind_param("ii", $user_id, $user_type); 
    $sqlCheck->execute();

    $resultCheck = $sqlCheck->get_result();

    if (!empty($resultCheck)) {
        if ($resultCheck->num_rows == 0) {
            $response["success"] = 0;
            $response["message"] = "Cannot delete user. Application requires atleast one admin account.";
     
            // echo no users JSON
            echo json_encode($response);
            die();
        }
    }

    $sql = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $sql->bind_param("i",$user_id);          
    $sql->execute();

    // check if row deleted or not
    if ($con->affected_rows > 0) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully deleted";
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No user found";
 
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