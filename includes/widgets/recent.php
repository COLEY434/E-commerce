<h3 class="text-center">Popular items</h3>
<?php
$transQ = $dbconn->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");

$results = array();

while($row = mysqli_fetch_assoc($transQ)){
	$results[] = $row;
}
$row_count = $transQ->num_rows;
$used_id = array();
for($i=0; $i < $row_count; $i++){
	$json_items = $results[$i]['item'];
	$items = json_decode($json_items, true);
	foreach ($items as $item) {
		if(!in_array($item['id'], $used_id)){
			$used_id[] = $item['id'];
		}
	}
}

?>
<div id="recent-widget">
	<table class="table table-condensed">
		<?php
		foreach ($used_id as $id) : 
		$productQ = $dbconn->query("SELECT id,title FROM products WHERE id = '{$id}'");
		$product = mysqli_fetch_assoc($productQ);
		?>
		<tr>
			<td><?=substr($product['title'],0,13);?></td>
			<td>
				<a class="btn btn-xs btn-primary" onclick="detailsmodal(<?=$id;?>)">View</a>
			</td>
		</tr>

		
		<?php endforeach; ?>
	</table>
</div>







