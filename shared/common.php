<?php
function generate_password_hash($userpwd, $salt) {
	return $encrypted = hash_hmac("sha256", $userpwd, $salt);	
}


function generate_salt() {
	$salt = uniqid(mt_rand()).uniqid(mt_rand());
	$salt = substr($salt,0,32);
	return $salt;	
}
?>