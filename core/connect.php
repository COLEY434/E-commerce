<?php

$dbconn = mysqli_connect('localhost','root','colley02','ecommerce');
if (mysqli_connect_errno()) {  

	echo "database connection failed due to the following errors".mysqli_connect_error();
	die();
}

session_start();
 
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/helpers/helpers.php';
require BASEURL.'/vendor/autoload.php';
 
$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])) {
	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
	
}            



//getting ang setting user id for login purposes
if (isset($_SESSION['user'])){
	$user_id = $_SESSION['user'];
	$query = $dbconn->query("SELECT * FROM users WHERE id ='$user_id'");
	$user_data = mysqli_fetch_assoc($query);
	$fn = explode(' ', $user_data['full_name']);
	$user_data['first'] = $fn[0];
	$user_data['last'] = $fn[1];
}

if(isset($_SESSION['success_flash'])){
	echo '<div class="bg-success"><p class="text-center text-success">'.$_SESSION['success_flash'].'</p></div>';
	unset($_SESSION['success_flash']);
}
if(isset($_SESSION['errors_flash'])){
	echo '<div class="bg-danger"><p class="text-center text-danger">'.$_SESSION['errors_flash'].'</p></div>';
	unset($_SESSION['errors_flash']);
}

?>