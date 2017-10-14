<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
if (!is_logged_in()){
	logged_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';

if (isset($_GET['delete'])){
	$delete_id = sanitize($_GET['delete']);
	$dbconn->query("UPDATE products SET deleted = 1 WHERE id = '$delete_id'");
	header("Location: products.php");
}

$dbpath = '';

if (isset($_GET['add']) || isset($_GET['edit'])){
	$brandQuery = $dbconn->query("SELECT * FROM brand ORDER BY brand");
	$categoriesQuery = $dbconn->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

	$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):'');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = ((isset($_POST['parent_category']) && !empty($_POST['parent_category']))?sanitize($_POST['parent_category']):'');
	$category = ((isset($_POST['child_category']) && !empty($_POST['child_category']))?sanitize($_POST['child_category']):'');
	$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):'');
	$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):'');
	$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):'');
	$sizes = rtrim($sizes, ',');
	$saved_image = '';

	if (isset($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$productResult = $dbconn->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($productResult);

		if (isset($_GET['delete_image'])) {
				$imgi = (int)$_GET['imgi'] - 1;
				$images = explode(',',$product['image']);
				 $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
				 unlink($image_url);
		     unset($images[$imgi]);
				 $imageString = implode(',',$images);
		     $dbconn->query("UPDATE products SET image = '{$imageString}' WHERE id = '$edit_id'");
		     header('Location: products.php?edit='.$edit_id);
		}
		$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
		$category = ((isset($_POST['child_category']) && !empty($_POST['child_category']))?sanitize($_POST['child_category']):$product['categories']);
		$parentQ = $dbconn->query("SELECT * FROM categories WHERE id = '$category'");
		$parentResult = mysqli_fetch_assoc($parentQ);
		$parent = ((isset($_POST['parent_category']) && !empty($_POST['parent_category']))?sanitize($_POST['parent_category']):$parentResult['parent']);
		$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
		$list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
		$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
		$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
		$sizes = rtrim($sizes, ',');
		$saved_image = (($product['image'] != '')?$product['image']:'');
		$dbpath = $saved_image;


	}

						if(!empty($sizes)){
							$sizeString = sanitize($sizes);
							$sizeString = rtrim($sizeString, ',');
							$sizesArray = explode(',', $sizeString);
							$sArray = array();
							$qArray = array();
							$tArray = array();
							foreach($sizesArray as $ss) {
								$string = explode(':', $ss);
								$sArray[] = $string[0];
								$qArray[] = $string[1];
								$tArray[] = $string[2];
							}
						}
	 				 	else
	  					{
						$sizesArray = array();
	  					}

	if ($_POST){
		$errors = array();
	    $required = array('title','brand','parent_category','child_category','price','sizes','description');
			$allowedFormat = array('jpg', 'jpeg', 'png', 'gif');
			$tempLoc = array();
			$uploadPath = array();
	    	foreach ($required as $field) {
	    		if($_POST[$field] == ''){
	    			$errors[] = "All field with an asterik is required";
	    			break;
	    		}
	    	}

			 $photoCount = count($_FILES['photo']['name']);
	     if ($photoCount > 0){
				 for($i = 0; $i < $photoCount; $i++){
	    			$name = $_FILES['photo']['name'][$i];
	    			$nameArray = explode('.', $name);
	    			$fileName = $nameArray[0];
	    			$fileNameExt = $nameArray[1];
	    			$typeOfphoto = explode('/', $_FILES['photo']['type'][$i]);
	    			$fileTypeName = $typeOfphoto[0];
	    			$fileTypeExt = $typeOfphoto[1];
	    			$tempLoc[] = $_FILES['photo']['tmp_name'][$i];
	    			$fileSize = $_FILES['photo']['size'][$i];
	    			$uploadName = md5(microtime().$i).'.'.$fileNameExt;
	    			$uploadPath[] = BASEURL.'images/products/'.$uploadName;
						if($i != 0){
							$dbpath .= ',';
						}
	    			$dbpath .= '/E-commerce/images/products/'.$uploadName;

			    	if(!in_array($fileNameExt, $allowedFormat)){
			    		$errors[] = "Only files with png, jpg, jpeg and gif extensions are allowed.";
			    	}
			    	if($fileSize > 10000000) {
			    		$errors[] = "The Image must be less than 10mb.";
			    	}
						if($fileNameExt != $fileTypeName && ($fileTypeName == 'jpeg' && $fileNameExt != '')){
							$errors[] = "file extension does not match the file";
						}



		}
}
	    if(!empty($errors)) {
	    	echo display_Errors($errors);
	    }else {
	    	//upload file and insert into database
	    	if($photoCount > 0){
					for($i = 0; $i < $photoCount; $i++){
	    	move_uploaded_file($tempLoc[$i], $uploadPath[$i]);
					}
	    	}
	    	$insertSql = "INSERT INTO products (`title`,`price`,`list_price`,`brand`,`categories`,`image`,`description`,`sizes`) VALUES
	    	 ('$title','$price','$list_price','$brand','$category','$dbpath','$description','$sizes')";
	    	 if (isset($_GET['edit'])){
	    	 	$insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$category', image = '$dbpath', description = '$description', sizes = '$sizes' WHERE id = '$edit_id'";
	    	 }
	    	$dbconn->query($insertSql);
	    	header("Location: products.php");
	    }

    }
?>
<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a new');?> product</h2><hr>
<form method="POST" action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" enctype="multipart/form-data">
	<div class="col-md-3 form-group">
		<label for="title">Title*:</label>
		<input type="text" name="title" id="title" class="form-control" value="<?=$title;?>">
	</div>
	<div class="col-md-3 form-group">
		<label for="brand">Brand*:</label>
			<select name="brand" id="brand" class="form-control">
				<option value=""<?=(($brand == '')?' selected':'');?>></option>
				<?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
					<option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
				<?php endwhile; ?>
			</select>
	</div>

	<div class="col-md-3 form-group">
		<label for="parent_category">Parent category*:</label>
			<select name="parent_category" id="parent_category" class="form-control">
				<option value=""<?=(($parent == '')?' selected':'');?>></option>
				<?php while($p = mysqli_fetch_assoc($categoriesQuery)): ?>
					<option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':'');?>><?=$p['category'];?></option>
				<?php endwhile; ?>
			</select>
	</div>

	<div class="col-md-3 form-group">
		<label for="child_category">Child category*:</label>
			<select name="child_category" id="child_category" class="form-control">
			</select>
	</div>
	<div class="form-group col-md-3">
		<label for="price">Price*:</label>
		<input type="text" name="price" id="price" class="form-control" value="<?=$price;?>">
	</div>
	<div class="form-group col-md-3">
		<label for="list_price">List Price:</label>
		<input type="text" name="list_price" id="list_price" class="form-control" value="<?=$list_price;?>">
	</div>
	<div class="form-group col-md-3">
		<label>Quantity & Sizes*:</label>
		<button type="button" class="btn btn-primary form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">
  		Quantity & sizes
		</button>
	</div>
	<div class="form-group col-md-3">
		<label for="sizes">Quantity & size preview</label>
		<input type="text" name="sizes" id="sizes" class="form-control" value="<?=$sizes;?>" readonly>
	</div>
	<div class="form-group col-md-6">
	<?php if($saved_image != ''): ?>
		<?php
		$imgi = 1;
		 $images = explode(',',$saved_image);?>

		<?php foreach($images as $image) : ?>
		<div class="saved_image col-md-4"><img src="<?=$image;?>"><br>
		<a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete image</a>
	</div>
		<?php
		$imgi++;
	endforeach;?>
		<?php else: ?>
		<label for="photo">Add Product Photo*:</label>
		<input type="file" name="photo[]" id="photo" class="form-control" multiple>
		<?php endif;?>
	</div>
	<div class="form-group col-md-6">
		<label for="description">Description*:</label>
		<textarea class="form-control" name="description" id="description" rows="6"><?=$description;?></textarea>
	</div>
	<div class="form-group col-md-3 pull-right">
		<a href="products.php" class="btn btn-info">Cancel</a>
		<input type="submit" name="add_product" class="btn btn-success" value="<?=((isset($_GET['edit']))?'Edit Product':'Add Product');?>">
	</div>

	<div class="clearfix"></div>
</form>

<br><br>



<!--modal for the quantity size -- >
<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Sizes</h4>
      </div>
      <div class="modal-body">
            <div class="container-fluid">
 		<?php for($i=1; $i <= 5; $i++): ?>
 			<div class="col-md-2 form-group">
 				<label for="size<?=$i;?>">Sizes:</label>
 				<input type="text" name="size<?=$i;?>" id="size<?=$i;?>" class="form-control" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
 			</div>

 			<div class="col-md-2 form-group">
 				<label for="qty<?=$i;?>">Quantity:</label>
 				<input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" min="0" class="form-control" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>">
 			</div>
			<div class="col-md-2 form-group">
 				<label for="threshold<?=$i;?>">Threshold:</label>
 				<input type="number" name="threshold<?=$i;?>" id="threshold<?=$i;?>" min="0" class="form-control" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:'');?>">
 			</div>
 		<?php endfor; ?>
 			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>


<?php
}
else{
$sql = "SELECT * FROM products WHERE deleted = 0";
$presult = $dbconn->query($sql);
if (isset($_GET['featured'])) {
	$id = (int)$_GET['id'];
	$featured = (int)$_GET['featured'];
	$updatesql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
	$dbconn->query($updatesql);
	header("Location: products.php");
}

?>

<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add_product_button">Add product</a><div class="clearfix"></div>
<hr>
<table class="table table-condensed table-bordered table-striped">
<thead><th>Edit or Delete</th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
<tbody>

<?php while($product = mysqli_fetch_assoc($presult)):
    //collecting the value in the categories column in the product table
	$product_categories_id = $product['categories'];
	//querying the categories table and
	//checking if the id in the categories table is equal to the value in the products categories column

	$sql1 = "SELECT * FROM categories WHERE id = '$product_categories_id'";
	$result1 = $dbconn->query($sql1);
	$childParent = mysqli_fetch_assoc($result1);
	$parentId = $childParent['parent'];

	$sql2 = "SELECT * FROM categories WHERE id = '$parentId'";
	$result2 = $dbconn->query($sql2);
	$parent = mysqli_fetch_assoc($result2);
	$category = $parent['category'].'~'.$childParent['category'];

	?>

<tr>
	<td>
		<a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>
		<a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
	</td>
	<td><?=$product['title'];?></td>
	<td><?=money($product['price']);?></td>
	<td><?=$category;?></td>
	<td><a href="products.php?featured=<?=(($product['featured'] == 0)?1:0);?>&id=<?=$product['id'];?>" class="btn btn-xs btn-success">
	<span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span></a>
	&nbsp;<?=(($product['featured'] == 1)?'Featured product':'');?></td>
	<td>0</td>
</tr>

<?php endwhile; ?>
</tbody>

</table>

<?php
}
include 'includes/footer.php';
?>

<script type="text/javascript">
	jQuery('document').ready(function(){
		get_child_options('<?=$category;?>');
	});
</script>
