<?php
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
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 
// array for JSON response
$response = array();
 
// check for required fields
if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
 
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password) {
        $response["success"] = 0;
        $response["message"] = "Password does not match!";
 
        // echoing JSON response
        echo json_encode($response);
        die();
    }

 
    // mysql inserting a new row
    // $result = mysql_query("INSERT INTO products(name, price, description) VALUES('$name', '$price', '$description')");

    $sql_check = $con->prepare("SELECT * FROM users WHERE username=?");
    $sql_check->bind_param("s",$username);          
    $sql_check->execute();
    $result_check = $sql_check->get_result();
    if ($result_check->num_rows > 0) {    
        $row = $result_check->fetch_assoc();

        $response["success"] = 0;
        $response["message"] = "Oops! Username already exists.";
        $response["user_id"] = $row['user_id'];
        $response["error_code"] = 'ALREADY_EXISTS';
 
        // echoing JSON response
        echo json_encode($response);
        die();
    }
    ///////////////
    $salt = generate_salt();
    $encrypted_password = generate_password_hash($password, $salt);
    $user_type = 2;
    ///////////////
    $sql = $con->prepare("INSERT INTO users(name, username, password, salt, user_type) VALUES(?, ?, ?, ?, ?)");    
    $sql->bind_param("ssssi",$name, $username, $encrypted_password, $salt, $user_type);    

    $result = $sql->execute();
 
    // check if row inserted or not
    if ($result) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "User successfully created.";
        $response["pid"] = $con->insert_id;
 
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
 
        // echoing JSON response
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