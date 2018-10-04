<?php
$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

$response = array('response' => 'error', 'message' => '');

if($username == 'polskie' && $password == '123456') {
	$response['response'] = 'ok';
	$response['message'] = 'Login Success!';
	$response['name'] = 'John Paul Fernandez';
}
else {
	$response['response'] = 'failed';
	$response['message'] = 'Invalid Login!';
}

echo json_encode($response);
?>