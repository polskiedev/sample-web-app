<?php
require_once __DIR__ . '/api_autoload.php';
 
/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
 
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
    }
    else {
        define("SESSMGR", $oCheckApiKey);
    }
}

// array for JSON response
$response = array();
 
// check for post data
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
 
    // get a product from products table
    // $result = mysql_query("SELECT *FROM products WHERE pid = $pid");
 
    $sql = $con->prepare("SELECT * FROM users WHERE username=? or email=?");
    $sql->bind_param("ss", $username, $username);          
    $sql->execute();
    $result = $sql->get_result();

    if (!empty($result)) {
        if ($result->num_rows == 1) {
			while ($row = $result->fetch_assoc()) {
				$salt =  $row["salt"];
				$encrypted = generate_password_hash($password, $salt);

				// echo $salt.'-'.$encrypted;die();
			    $sql2 = $con->prepare("SELECT * FROM users WHERE (username=? or email=?) and password=?");
			    $sql2->bind_param("sss", $username, $username, $encrypted);          
			    $sql2->execute();
			    $result2 = $sql2->get_result();

			    if (!empty($result2)) {
			        if ($result2->num_rows == 1) {
                        while ($row2 = $result2->fetch_assoc()) {
    			            $response["success"] = 1;
                            $response["message"] = "Please wait..."; 
    						$response["login_data"] = array(
                                'PROFILE_REAUTH_EMAIL' => $row2['username'],
                                'PROFILE_NAME' => $row2['name'],
                                'PROFILE_IMG_SRC' => $row2['profile_picture']
                            ); 

                            $response["user_data"] = array(
                                'user_id' => $row2['user_id'],
                                'username' => $row2['username'],
                                'name' => $row2['name'],
                                'user_type' => $row2['user_type']
                                // 'PROFILE_IMG_SRC' => $row2['profile_picture']
                            ); 


                            if(defined("SESSMGR")) {
                                $response["user_data"]["sess_mgr"] = SESSMGR;
                            }
    			            // // echoing JSON response
    			            echo json_encode($response);
    			            die();
                        }
			        }
			    }	
 			}
        } 
    }

    $response["success"] = 0;
    $response["message"] = "Your username or password is incorrect!";
    echo json_encode($response);
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>