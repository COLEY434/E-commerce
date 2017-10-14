<?php
require_once '../core/connect.php';
if (!is_logged_in()){
	header("Location: login.php");
}

include 'includes/header.php';
include 'includes/navigation.php';

?>

<!--orders to fill-->
<?php
	$txnQuery = "SELECT t.id, t.cart_id, t.full_name, t.description, t.txn_date, t.grand_total, c.item, c.paid, c.shipped
	FROM transactions t
	LEFT JOIN cart c ON t.cart_id = c.id
	WHERE c.paid = 1 AND c.shipped = 0
	ORDER BY t.txn_date";
	$txnResult = $dbconn->query($txnQuery);
 ?>
<div class="row">
	<div class="col-md-12">
				<h3 class="text-center">Orders to ship</h3>
					<table class="table table-condensed table-striped table-bordered">
						<thead><th></th><th>Name</th><th>Description</th><th>Total</th><th>Date</th></thead>
						<tbody>
							<?php while($order = mysqli_fetch_assoc($txnResult)):?>
							<tr>
								 <td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-info btn-xs">Details</a></td>
								  <td><?=$order['full_name'];?></td>
									 <td><?=$order['description'];?></td>
									  <td><?=money($order['grand_total']);?></td>
										 <td><?=preety_time($order['txn_date']);?></td>
							</tr>
			<?php endwhile;?>
						</tbody>
					</table>
		</div>
</div>


<div class="row">
	<!--sales by month -->
	<?php
		$thisYr = date("Y");
		$lastYr = $thisYr - 1;
		$thisYrQ = $dbconn->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date) = '{$thisYr}'");
		$lastYrQ = $dbconn->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date) = '{$lastYr}'");
		$current = array();
		$last = array();
		$currentTotal = 0;
		$lastTotal = 0;
		while ($x = mysqli_fetch_assoc($thisYrQ)) {
			$month = date("m",strtotime($x['txn_date']));
			if(!array_key_exists($month,$current)){
				$current[(int)$month] = $x['grand_total'];
			}
			else{
				$current[(int)$month] += $x['grand_total'];
			}
			$currentTotal += $x['grand_total'];
		}

		while ($y = mysqli_fetch_assoc($lastYrQ)) {
			$month = date("m",strtotime($y['txn_date']));
			if(!array_key_exists($month,$last)){
				$last[(int)$month] = $y['grand_total'];
			}
			else{
				$last[(int)$month] += $y['grand_total'];
			}
			$lastTotal += $y['grand_total'];
		}
	?>
<div class="col-md-4">
		<h3 class="text-center">Sales By Month</h3>
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<th></th>
				<th><?=$lastYr;?></th>
				<th><?=$thisYr;?></th>
			</thead>
			<tbody>
				<?php for ($i=1; $i <= 12 ; $i++):
					$dt = DateTime::createFromFormat('!m', $i);
				?>
				<tr <?=(date('m') == $i)?' class="info"':'';?>>
					<td><?=$dt->format("F");?></td>
					<td><?=(array_key_exists($i, $last)?money($last[$i]):money(0));?></td>
					<td><?=(array_key_exists($i, $current)?money($current[$i]):money(0));?></td>
				</tr>
			<?php endfor; ?>
			<tr>
				<td>Total</td>
				<td><?=money($lastTotal);?></td>
				<td><?=money($currentTotal);?></td>
			</tr>
			</tbody>
		</table>
</div>
<!--inventory-->
<?php
$iQuery = $dbconn->query("SELECT * FROM products WHERE deleted = 0");
$lowItems = array();
while($product = mysqli_fetch_assoc($iQuery)){
	$item = array();
	$sizes = sizesToArray($product['sizes']);
	foreach ($sizes as $size) {
		if($size['quantity'] <= $size['threshold']){
		$cat = get_category($product['categories']);
		$item = array(
			'title' => $product['title'],
			'size'  => $size['size'],
			'quantity' => $size['quantity'],
			'threshold' => $size['threshold'],
			'category' => $cat['parent'].'~'.$cat['child']
		);
		$lowItems[] = $item;
	}
}
}

 ?>
<div class="col-md-8">
	<h3 class="text-center">Low inventory</h3>
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<th>Product</th>
			<th>Category</th>
			<th>Size</th>
			<th>Quantity</th>
			<th>Threshold</th>
		</thead>
		<tbody>
			<?php foreach($lowItems as $item): ?>
			<tr <?=($item['quantity'] == 0)?' class="danger text-danger"':'';?>>
				<td><?=$item['title'];?></td>
				<td><?=$item['category'];?></td>
				<td><?=$item['size'];?></td>
				<td><?=$item['quantity'];?></td>
				<td><?=$item['threshold'];?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
</div>
<?php
include 'includes/footer.php';

?>
