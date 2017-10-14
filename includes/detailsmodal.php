<?php
require_once '../core/connect.php';
$id = $_POST['id'];
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $dbconn->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$sql1 = "SELECT brand FROM brand WHERE id = '$brand_id'";
$resutl1 = $dbconn->query($sql1);
$brand = mysqli_fetch_assoc($resutl1);
$sizes = $product['sizes'];
$sizes = rtrim($sizes, ',');
$sizearray = explode(',', $sizes);


?>
<!--Details modal -->
<?php ob_start();?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-header">
		 <button class="close" type="button" onclick="closeModal()" aria-label="close">
		 	<span aria-hidden="true">&times;</span>
		 </button>
		 <h4 class="modal-title text-center"><?=$product['title']; ?></h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<span id="modal-errors" class="bg-danger"></span>
					<div class="col-sm-6 fotorama" data-transition="crossfade">

						 <?php $photos = explode(',',$product['image']);
						 foreach ($photos as $photo) : ?>
  					 	<img src="<?=$photo;?>" style="border-radius: 7px" alt="<?=$product['title']; ?>" class="details img-responsive">
					 	 <?php endforeach;?>

					</div>
					<div class="col-sm-6">
						<h4>Details</h4>
						<p><?=nl2br($product['description']); ?></p>
						<hr>
						<p>Price: <?=$product['price']; ?></p>
						<p>Brand: <?=$brand['brand']; ?></p>


						<form action="add-cart.php" method="post" id="add_product_form">
							<input type="hidden" name="product_id" value="<?=$id;?>">
							<input type="hidden" name="available" id="available" value="">
							<div class="form-group">
								<div class="col-xs-3">
								 <label for="quantity">Quantity:</label>
								 <input type="number" name="quantity" min="0" max="120" id="quantity" class="form-control">
								</div>

							</div>
							<br><br>
							<div class="form-group">
								<label for="size">Size:</label>
								<select name="size" id="size" class="form-control">
									<option value=""></option>
									<?php
									foreach ($sizearray as $string) {
										$stringarray = explode(':', $string);
										$size = $stringarray[0];
										$available = $stringarray[1];
										if($available > 0){
										echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' '.'(Available: '.$available.')'.'</option>';
									}
								}

									?>


								</select>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-success" onclick="closeModal()">Close</button>
			<button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to cart</button>
		</div>
		</div>
	</div>
</div>
<script>
	jQuery('#size').change(function(){
		var available = jQuery('#size option:selected').data("available");
		jQuery('#available').val(available);
	});

	$(function () {
  $('.fotorama').fotorama({'loop':true,'autoplay':true});
  });

	function closeModal(){
		jQuery('#details-modal').modal('hide');
		setTimeout(function(){
			jQuery('#details-modal').remove();
			jQuery('.modal-backdrop').remove();
			},500);
	}
</script>
<?php echo ob_get_clean(); ?>
