<?php
// update_user.php
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
if (isset($_POST['user_id']) && isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['old_password'])) {

    $user_id        = $_POST['user_id'];
    $name           = $_POST['name'];
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $old_password = $_POST['old_password'];
    $updated_at     = date('Y-m-d H:i:s');

    $currentSalt    = '';
    $bChangePassword = false;
 
    $sql_check = $con->prepare("SELECT * FROM users WHERE username=? and user_id != ?");
    $sql_check->bind_param("si",$username,$user_id);          
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

    $sql_check2 = $con->prepare("SELECT * FROM users WHERE user_id = ?");
    $sql_check2->bind_param("i",$user_id);          
    $sql_check2->execute();
    $result_check2 = $sql_check2->get_result();
    if ($result_check2->num_rows == 1) {    
        $row2 = $result_check2->fetch_assoc();

        $currentSalt = $row2['salt'];
        $encrypted_old_password = generate_password_hash($old_password, $currentSalt);

        if($row2['password'] != $encrypted_old_password) {
            $response["success"] = 0;
            $response["message"] = "Old password do not match!";

            echo json_encode($response);
            die();
        }
    }

    if(!empty($password) && !empty($confirm_password)) {
        if(!empty($currentSalt))
        {        
            $bChangePassword = true;

            if($password != $confirm_password) {
                $response["success"] = 0;
                $response["message"] = "Password does not match!";
         
                // echoing JSON response
                echo json_encode($response);
                die();
            }
        }
    }

    // mysql update row with matched pid
    //$result = $con->prepare("UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE pid = $pid");
    $sql = $con->prepare("UPDATE users SET name=? , username=? , updated_at=? WHERE user_id=?");    
    $sql->bind_param("sssi",$name, $username, $updated_at, $user_id);    

    if($bChangePassword) {
        $encrypted_password = generate_password_hash($password, $currentSalt);

        $sql = $con->prepare("UPDATE users SET name=? , username=? , password=? , updated_at=? WHERE user_id=?");    
        $sql->bind_param("ssssi",$name, $username, $encrypted_password, $updated_at, $user_id);            
    }


    $result = $sql->execute();
    //////////////////////

    // check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";
 
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