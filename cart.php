<?php
require 'core/connect.php';
include 'includes/header.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';


if ($cart_id != ''){
	$cartQ = $dbconn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
	$product = mysqli_fetch_assoc($cartQ);
	$items = json_decode($product['item'],true);
	$i = 1;
	$sub_total = 0;
	$item_count = 0;
}
?>
    
<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My shoppping Cart</h2><hr>
		<a href="index.php" class="btn btn-info pull-right"><span class="glyphicon glyphicon-home"></span>Home</a>
		<div class="clearfix"></div>
		<?php if($cart_id == '') : ?>
			<div class="bg-danger">
				<p class="text-center text-danger">Your shopping cart is empty</p>
			</div>
		<?php else : ?>
			<table class="table table-striped table-condensed table-bordered">
				<thead><th>#</th><th>Product</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub-Total</th></thead>
				<tbody>
					<?php 
					foreach ($items as $item) {
						$product_id = $item['id'];
						$productQ = $dbconn->query("SELECT * FROM products WHERE id = '{$product_id}'");
						$product = mysqli_fetch_assoc($productQ);
						$sizeArray = explode(',', $product['sizes']);
						foreach ($sizeArray as $sizeString) {
							$s = explode(':',$sizeString);
							if($s[0] == $item['size']){
								$available = $s[1];
							}
						}
					
					?>
					<tr>
					<td><?=$i;?></td>
					<td><?=$product['title'];?></td>
					<td><?=money($product['price']);?></td>
					<td>
						<button class="btn btn-xs btn-default" onclick="update_cart('Removeone','<?=$product['id'];?>','<?=$item['size'];?>')">-</button>
						<?=$item['quantity'];?>
						<?php if($item['quantity'] < $available) : ?>
						<button class="btn btn-xs btn-default" onclick="update_cart('Addone','<?=$product['id'];?>','<?=$item['size'];?>')">+</button>
						<?php else : ?>
						 	<span class="text-danger">Maximum Reached</span>
						<?php endif;?>
						</td>
					<td><?=$item['size'];?></td>
					<td><?=money($item['quantity'] * $product['price']);?></td>
				</tr>

					<?php 
					$i++;
					$item_count += $item['quantity'];
					$sub_total += ($item['quantity'] * $product['price']);

					$tax = TAXRATE * $sub_total;
					$tax = number_format($tax,2);
					$grand_total = $tax + $sub_total;

				       }?>
					
				</tbody>

			</table>
			<table class="table table-bordered table-condensed table-striped text-right">
				<legend>Totals</legend>
				<thead>
					<thead class="totals-tables-header"><th>Total items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th></thead>
				</thead>
				<tr>
					<td><?=$item_count;?></td>
					<td><?=money($sub_total);?></td>
					<td><?='&#x20A6;'.$tax;?></td>
					<td class="bg-success text-success"><?=money($grand_total);?></td>
				</tr>
			</table>

	<button type="button" class="btn btn-primary pull-right" data-target="#myModal" data-toggle="modal"><span class="glyphicon glyphicon-shopping-cart"></span>CheckOut >></button>
	
	

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Shipping Address</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
       	 	<form method="POST" action="ThankYou.php" id="payment-form" autocomplete="on">
       	 		<!--<span class="bg-danger text-danger" id="payment-errors"></span>-->
       	 		<input type="hidden" name="tax" value="<?=$tax;?>">
       	 		<input type="hidden" name="sub_total" value="<?=$sub_total;?>">
       	 		<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
       	 		<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
       	 		<input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count > 1)?'s':'').' from collins boutique.';?>">  
        		<div id="step1" style="display: block;">
        			<div class="col-md-6 form-group">
                                        <label for="full_name">Full Name:</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="Street">Street Address 1:</label>
                                        <input type="text" name="Street" id="Street" class="form-control" data-stripe="address_line1">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="Street1">Street Address 2:</label>
                                        <input type="text" name="Street1" id="Street1" class="form-control" data-stripe="address_line2">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="country">Country:</label>
                                        <input type="text" name="country" id="country" class="form-control" data-stripe="address_country">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="city">City:</label>
                                        <input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="state">State:</label>
                                        <input type="text" name="state" id="state" class="form-control" data-stripe="address_state">
                                </div>

                                <div class="col-md-6 form-group">
                                        <label for="zipcode">Zip Code:</label>
                                        <input type="text" name="zipcode" id="zipcode" class="form-control" data-stripe="address_zip" >
                                </div>

        		</div>
        		<div id="step2" style="display: none;">
  
  	<div class="form-row col-md-6">
   	 	<label for="card-element">
      		Credit or debit card
   		 </label>
    <div id="card-element" class="StripeElement StripeElement--focus StripeElement--invalid StripeElement--webkit-autofill">
      <!-- a Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors -->
    <div id="card-errors" role="alert"></div>
  	</div>
  	
        		</div>
        		
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_Address();" id="next_button">Next</button>
        <button type="button" class="btn btn-primary" onclick="back_Address();" id="back_button" style="display: none">Previous</button>
   	    <button type="submit" class="btn btn-primary" id="check_out_button" style="display: none">Check Out</button>
        

</form>

        
      </div>
    </div>
  </div>
</div>

<?php endif;?>
	</div>
	
</div>
		
<script>

	function back_Address(){
		jQuery('#payment-errors').html("");
		jQuery('#step1').css("display","block");
		jQuery('#step2').css("display","none");
		jQuery('#next_button').css("display","inline-block");
		jQuery('#back_button').css("display","none");
		jQuery('#check_out_button').css("display","none");
		jQuery('#sizesModalLabel').html("Shipping Address");

	}
		
	function check_Address(){

		var data = {
			'full_name' : jQuery('#full_name').val(),
			'email' : jQuery('#email').val(),
			'Street' : jQuery('#Street').val(),
			'Street1' : jQuery('#Street1').val(),
			'country' : jQuery('#country').val(),
			'city' : jQuery('#city').val(),
			'state' : jQuery('#state').val(),
			'zipcode' : jQuery('#zipcode').val(),
		};

	jQuery.ajax({
			url : '/E-commerce/admin/parsers/check_address.php',
			method : 'post',
			data : data,
			success : function(data){
				if ($.trim(data) == "PASSED"){
				//alert('nrpoiuy');
				//document.getElementById("#payment-errors").innerHTML = "";
				data = "";
				jQuery('#step1').css("display","none");
				jQuery('#step2').css("display","block");
				jQuery('#next_button').css("display","none");
				jQuery('#back_button').css("display","inline-block");
				jQuery('#check_out_button').css("display","inline-block");
				jQuery('#sizesModalLabel').html("Enter your card details");

				//jQuery('.payY').val("hello");
			}

			if(data != "PASSED"){
				jQuery('#payment-errors').html(data);

			}
},
			error : function(){alert("something went wrong")},
		});
}


 
var stripe = Stripe('pk_test_2NGKTMAyxFBzYw1I5xqk3oHE');
var elements = stripe.elements();
var style = {
  base: {
    color: '#32325d',
    lineHeight: '24px',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>
card.mount('#card-element');

card.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});


function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}

// Create a token or display an error when the form is submitted.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server
      stripeTokenHandler(result.token);
    }
  });
});


</script>
<?php include 'includes/footer.php';?>