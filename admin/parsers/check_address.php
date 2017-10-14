<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['Street']);
$street1 = sanitize($_POST['Street1']);
$country = sanitize($_POST['country']);
$state = sanitize($_POST['state']);
$city = sanitize($_POST['city']);
$zipcode = sanitize($_POST['zipcode']);

$errors = array();
$required = array(
	'full_name' => 'Full Name',
	'email' 	=> 'Email',
	'Street'	=> 'Street Address',
	'country'   => 'Country',
	'state'     => 'State',
	'zipcode'  => 'Zip Code',
	'city'      => 'City',
		 );

foreach ($required as $field => $value) {
	if(empty($_POST[$field]) || $_POST[$field] == ''){
		$errors[] = $value.' is required';
	}
}

  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
   $errors[] = 'Please enter a valid email Address';
   }

if(!empty($errors)){
	echo display_Errors($errors);
}else {
	echo 'PASSED';
}


?>    