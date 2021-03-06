<?php
require_once 'core/connect.php';
include 'includes/header.php';
include 'includes/navigation.php';
include 'includes/headerfull.php';

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $dbconn->query($sql);

?>



<?php include 'includes/leftbar.php';?>

	<!-- main content -->
	<div class="col-md-8">
	  <div class="row">
	  <h2 class="text-center">Featured products</h2>
	  <?php while($product = mysqli_fetch_assoc($featured)) : ?>
	  	<div class="col-md-3 text-center">

	  	<h4><?= $product['title']; ?></h4>
			<?php $photos = explode(',',$product['image']);?>
		  	<img src="<?= $photos[0]; ?>"  height="100%" class="img-thumbnail" alt="<?= $product['title']; ?>">
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
