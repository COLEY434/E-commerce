<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
$mode = sanitize($_POST['mode']);
$edit_size = sanitize($_POST['edit_size']);
$edit_id = sanitize($_POST['edit_id']);
$cartQ = $dbconn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['item'],true);
$updated_items = array();
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;


if($mode == 'Removeone'){
	foreach ($items as $item) {
		if(($item['id'] == $edit_id) && ($item['size'] == $edit_size)){
			$item['quantity'] = $item['quantity'] - 1;
		}
		if($item['quantity'] > 0){
			$updated_items[] = $item;
		}
	}
}

if($mode == 'Addone'){
	foreach ($items as $item) {
		if($item['id'] == $edit_id && $item['size'] == $edit_size){
			$item['quantity'] = $item['quantity'] + 1;
		}
		$updated_items[] = $item;
	}
}

if(!empty($updated_items)){
	$updated_encode = json_encode($updated_items);
	$dbconn->query("UPDATE cart SET item = '{$updated_encode}' WHERE id = '{$cart_id}'");
	$_SESSION['success_flash'] = "Your cart has been updated";
}

if(empty($updated_items)){
	$dbconn->query("DELETE FROM cart WHERE id = '{$cart_id}'");
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
}
?>