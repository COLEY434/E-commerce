<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
$product_id = sanitize($_POST['product_id']);
$size = sanitize($_POST['size']);
$available = sanitize($_POST['available']);
$quantity = sanitize($_POST['quantity']);

$item = array();
$item[] = array(
	'id' => $product_id,
	'size' => $size,
	'quantity' => $quantity,
);

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query = $dbconn->query("SELECT * FROM products WHERE id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);
$_SESSION['success_flash'] = $product['title'].' was added to your cart';

//check to see if cart exists in the database


if($cart_id != ''){
$cartQ = $dbconn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$cart = mysqli_fetch_assoc($cartQ);
$previous_item = json_decode($cart['item'],true);
$item_match = 0;
$new_items = array();
foreach ($previous_item as $pitem) {
	//var_dump($pitem);
	if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']){
		$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
		if($pitem['quantity'] > $available){
			$pitem['quantity'] = $available;
		}
		$item_match = 1;
	}

	$new_items[] = $pitem;
}
if($item_match != 1){
	$new_items = array_merge($item,$previous_item);
}
$items_json = json_encode($new_items);
$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
$dbconn->query("UPDATE cart SET item = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
setcookie(CART_COOKIE,'',1,"/",$domain,false);
setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,"/",$domain,false);
}
else{
	//add the cart to the database and set cookie
	$items_json = json_encode($item);
	$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
	$dbconn->query("INSERT INTO cart (item,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
	$cart_id = $dbconn->insert_id;
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,"/",$domain,false);
}
?>
