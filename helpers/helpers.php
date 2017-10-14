<?php

function display_Errors($errors) {
	$display = '<ul class="bg-danger">';
	foreach ($errors as $error) {
		$display .= '<li>'.$error.'</li>';
	}
	$display .= '</ul>';
	return $display;

}

function sanitize($dirty) {
return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}

function money($number) {
	return '&#x20A6;'.number_format($number, 2);
}

function login($user_id) {
	$_SESSION['user'] = $user_id;
	global $dbconn;
	$date = date("Y-m-d H:i:s");
	$dbconn->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
	$_SESSION['success_flash'] = "You are now logged in!";
	header("Location: index.php");
}

function is_logged_in() {
	if (isset($_SESSION['user']) && $_SESSION['user'] > 0) {
		return true;
	}
	else
	{
	return false;
}
}
function logged_error_redirect($url = 'login.php') {
	$_SESSION['errors_flash'] = "You must be logged in to access this page";
	header('Location: '.$url);
}
function permissions_errors_redirect($url = 'login.php'){

$_SESSION['errors_flash'] = "You do not have permission to access that page";
	header('Location: '.$url);
}

function has_permission($permission) {
	global $user_data;
	$permissions = explode(',', $user_data['permissions']);

	if(in_array($permission,$permissions,true)){
		return true;
	}
	else{
		return false;
	}
}


function preety_time($time){
	return date("M d, Y h:i A", strtotime($time));
}

function get_category($child_id){
	global $dbconn;
	$id = sanitize($child_id);
	$sql = "SELECT p.id AS 'pid',p.category AS 'parent',c.id AS 'cid',c.category AS 'child'
			FROM categories c
			INNER JOIN categories p
			ON c.parent = p.id
			WHERE c.id = '$id'";
	$query = $dbconn->query($sql);
	$category = mysqli_fetch_assoc($query);
	return $category;

}

function sizesToArray($string){
	$sizesArray = explode(',', $string);
	$returnArray = array();
	foreach ($sizesArray as $size) {
	 	$s = explode(':',$size);
	 	$returnArray[] = array('size' => $s[0], 'quantity' => $s[1], 'threshold' => $s[2]);
	 }
	 return $returnArray;
}

function sizesToString($sizes){
	$sizeString = '';
	foreach ($sizes as $size) {
		$sizeString .= $size['size'].':'.$size['quantity'].':'.$size['threshold'].',';
	}
	$trimmed = rtrim($sizeString);
	return $trimmed;
}
