<?php
require 'core/connect.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey('sk_test_2c03akgIOrvLSqtOqgAuNrH7');

// Token is created using Stripe.js or Checkout!
// Get the payment token ID submitted by the form:
//$token = $_POST['stripeToken'];
$token = ((isset($_POST['stripeToken']))? $_POST['stripeToken']:'');
//get the rest of the data
$cardName = ((isset($_POST['cardName']))?sanitize($_POST['cardName']):'');
$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$Street = ((isset($_POST['Street']))?sanitize($_POST['Street']):'');
$Street1 = ((isset($_POST['Street1']))?sanitize($_POST['Street1']):'');
$country = ((isset($_POST['country']))?sanitize($_POST['country']):'');
$state = ((isset($_POST['state']))?sanitize($_POST['state']):'');
$city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
$zipcode = ((isset($_POST['zipcode']))?sanitize($_POST['zipcode']):'');
$tax = ((isset($_POST['tax']))?sanitize($_POST['tax']):'');
$sub_total = ((isset($_POST['sub_total']))?sanitize($_POST['sub_total']):'');
$grand_total = ((isset($_POST['grand_total']))?sanitize($_POST['grand_total']):'');
$cart_id = ((isset($_POST['cart_id']))?sanitize($_POST['cart_id']):'');
$description = ((isset($_POST['description']))?sanitize($_POST['description']):'');
$charge_amount = $grand_total * 100;
$metadata = array(
	"cart_id" 	=> $cart_id,
	"tax"		=> $tax,
	"sub_total" => $sub_total,

);
// Charge the user's card:
try{
$charge = \Stripe\Charge::create(array(
  "amount" => $charge_amount,
  "currency" => CURRENCY,
  "description" => $description,
  "source" => $token,
  "receipt_email"  => $email,
  "metadata" => $metadata)
);
//adjust inventory
$itemQ = $dbconn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$results = mysqli_fetch_assoc($itemQ);
$items = json_decode($results['item'],true);
foreach ($items as $item) {
	$newSizes = array();
	$item_id = $item['id'];
	$productQ = $dbconn->query("SELECT * FROM products WHERE id = '{$item_id}'");
	$product = mysqli_fetch_assoc($productQ);
	$sizes = sizesToArray($product['sizes']);

	foreach ($sizes as $size) {
		if($size['size'] == $item['size']){
			$q = $size['quantity'] - $item['quantity'];
			$newSizes[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $size['threshold']);
		}else
		{
			$newSizes[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
		}
	}
  $sizeString = sizesToString($newSizes);
  $dbconn->query("UPDATE products SET sizes = '{$sizeString}' WHERE id ='{$item_id}'");
}

//update cart
$dbconn->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
$sql = "INSERT INTO transactions (charge_id,cart_id,full_name,email,street,street1,city,state,zip_code,country,sub_total,tax,grand_total,description,txn_type)
     VALUES ('{$charge->id}','{$cart_id}','{$full_name}','{$email}','{$Street}','{$Street1}','{$city}','{$state}','{$zipcode}','{$country}','{$sub_total}','{$tax}','{$grand_total}','{$description}','{$charge->object}')";

if ($dbconn->query($sql)){

}
else {
	echo "Error: " . $sql . "<br>" . $dbconn->error;
}


$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',1,"/",$domain,false);
include 'includes/header.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
?>
<h1 class="text-center text-success">Thank you</h1>
<p>Your card has been successfully charges <?=money($grand_total);?></p>
<p>Your reciept number is: <strong><?=$cart_id;?></strong></p>
<p>Your order will be shipped to the address below</p>
<address>
	<?=$full_name;?><br>
	<?=$Street;?><br>
	<?=(($Street1 != '')?$Street1:'');?><br>
	<?=$city.', '.$state.', '.$zipcode;?><br>
	<?=$country;?><br>
</address>

<?php
include 'includes/footer.php';
} catch(\Stripe\Error\Card $e) {
	//the card has been declined

	echo $e;
}
