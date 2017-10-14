<h3 class="text-center">Shopping cart</h3>
<div>
	<?php if(empty($cart_id)) : ?>
		<p class="text-center">Your shopping cart is empty</p>
	<?php else: 
		$cartQ = $dbconn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$results = mysqli_fetch_assoc($cartQ);
		$items = json_decode($results['item'],true);
		$sub_total = '';

	?>
	<table class="table table-condensed table-striped" id="cart-widget">
		<tbody>
			<?php 
			foreach ($items as $item):
			  $productQ = $dbconn->query("SELECT * FROM products WHERE id = '{$item['id']}'");
			  $products = mysqli_fetch_assoc($productQ);
			  ?>
			<tr>
				<td><?=$item['quantity'];?></td>
				<td><?=substr($products['title'], 0,13);?></td>
				<td><?=money($item['quantity'] * $products['price']);?></td>
			</tr>
			<?php
			$sub_total += $item['quantity'] * $products['price'];
			endforeach;?>

			<tr>
				<td></td>
				<td>Sub-total</td>
				<td><?=money($sub_total);?></td>
			</tr>
		</tbody>
	</table>
<a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
<div class="clearfix"></div>

	<?php endif;?>
</div>