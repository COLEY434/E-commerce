<?php
require_once 'core/connect.php';
include 'includes/header.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';

$sql = "SELECT * FROM products";
$cat_id = ((isset($_POST['cat']))?sanitize($_POST['cat']):'');
$cat_id = (($cat_id != '')?$cat_id:'');
if ($cat_id == ''){
	$sql .= " WHERE deleted = 0";
}
else{
	$sql .= " WHERE categories = '{$cat_id}' AND deleted = 0";
}
$price_sort = ((isset($_POST['price_sort']))?sanitize($_POST['price_sort']):'');
$min_price = ((isset($_POST['min_price']))?sanitize($_POST['min_price']):'');
$max_price = ((isset($_POST['max_price']))?sanitize($_POST['max_price']):'');
$brand = ((isset($_POST['brand']))?sanitize($_POST['brand']):'');

$price_sort = (($price_sort != '')?$price_sort:'');
$min_price = (($min_price != '')?$min_price:'');
$max_price = (($max_price != '')?$max_price:'');
$brand = (($brand != '')?$brand:'');

if($min_price != ''){
	$sql .= " AND price >= '{$min_price}'";
}
if($max_price != ''){
	$sql .= " AND price <= '{$max_price}'";
}
if($brand != ''){
	$sql .= " AND brand = '{$brand}'";
}
if($price_sort == 'low'){
	$sql .= " ORDER BY price";
}
if($price_sort == 'high'){
	$sql .= " ORDER BY price DESC";
}
$categories = $dbconn->query($sql);
$category = get_category($cat_id);
?>



<?php include 'includes/leftbar.php';?>

	<!-- main content -->
	<div class="col-md-8">
	  <div class="row">
	<?php if($cat_id != '') : ?>
	  <h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>
	<?php else: ?>
		<h2 class="text-center">Collins boutiques</h2>
	<?php endif;?>
	  <?php while($product = mysqli_fetch_assoc($categories)) : ?>
	  	<div class="col-md-3 text-center">
	  	<h4><?= $product['title']; ?></h4>
			<?php $photos = explode(',',$product['image']);?>
		  	<img src="<?= $photos[0]; ?>"  class="img-thumbnail img-responsive" alt="<?= $product['title']; ?>">
		  	<p class="list-price text-danger">List price:<s>&#x20A6;<?= $product['list_price']; ?></s></p>
		  	<p class="price">our price: &#x20A6;<?= $product['price']; ?></p>
		  	<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?=$product['id']; ?>)">Details</button>
		  </div>
	  <?php endwhile; ?>
	  </div>
	</div>


<?php

include 'includes/rightbar.php';
include 'includes/footer.php';

?>
