<?php

define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/E-commerce/');
define('CART_COOKIE', 'SDSD787usfkzzkkda47d');
define('CART_COOKIE_EXPIRE', time() + (86400 * 30));
define('TAXRATE', '0.087');
 

 define('CURRENCY','usd');
 define('CHECKOUTMODE','TEST');//change test to live when you aree ready to go live



if(CHECKOUTMODE == 'TEST'){
	define('STRIPE_PRIVATE', 'sk_test_2c03akgIOrvLSqtOqgAuNrH7');
	define('STRIPE_PUBLIC', 'pk_test_2NGKTMAyxFBzYw1I5xqk3oHE');
}

if(CHECKOUTMODE == 'LIVE'){
	define('STRIPE_PRIVATE', '');
	define('STRIPE_PUBLIC', '');
}

?>
    