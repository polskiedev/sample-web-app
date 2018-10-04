<?php
$name = isset($_GET['name']) ? $_GET['name'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

// $response = array('response' => 'error');
$response = array('response' => 'error', 'message' => '');

if($username == 'polskie') {
	$response['response'] = 'exists';
}
else {
	$response['response'] = 'ok';
}

echo json_encode($response);
?>