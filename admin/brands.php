<?php
require_once '../core/connect.php';
if (!is_logged_in()){
	logged_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM brand ORDER BY brand";
$result = $dbconn->query($sql);
$errors = array();

//Edit brand
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
	$edit_id= (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);
	$sql1 = "SELECT * FROM brand WHERE id = '$edit_id'";
	$result1 = mysqli_query($dbconn, $sql1);
	$ebrand = mysqli_fetch_assoc($result1);
}

//delete brand

if(isset($_GET['delete']) && !empty($_GET['delete'])){
	$brand_id = (int)$_GET['delete'];
	$brand_id = sanitize($brand_id);
	$sql = "DELETE FROM brand WHERE id = '$brand_id'";
	$dbconn->query($sql);
	header("Location: brands.php");
}
// if add_submit is submitted

if (isset($_POST['add_submit'])){
	$brand = sanitize($_POST['brand']);
	//check if brand is blank
	if ($_POST['brand'] == ''){
		$errors[] .= 'you must enter a brand';
	}
	//check if brand exist in the database
	$sql = "SELECT * FROM brand WHERE brand = '$brand'";
	//update existing brand
	if(isset($_GET['edit'])) {
	   $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";

	}

	$result2 = $dbconn->query($sql);
	$count = mysqli_num_rows($result2);
	if ($count > 0){
		$errors[] .= $brand." brand already exist.Please choose another brand.";
	}
	//display errors
	if(!empty($errors)){
		echo display_Errors($errors);
	}
	else {
		//add brand to the database
		$sql = "INSERT INTO brand (brand) VALUES ('$brand')";
		//edit brand in the database
		if(isset($_GET['edit'])) {
			$sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
		}
		$result = $dbconn->query($sql);
		header("Location: brands.php");

	}
}

?>
<h2 class="text-center">Brands</h2><hr>
<!--brand form-->
<div class="text-center">
	<form method="post" action="Brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" class="form-inline">
		<div class="form-group">

		<?php
		
		$brand_value = '';
		 if(isset($_GET['edit'])) {
			$brand_value = $ebrand['brand'];
		} else {
			if(isset($_POST['edit'])){

			$brand_value = $brand;
		}
		}
		?>
			<label for="brand<?=$io;?>"><?=((isset($_GET['edit']))?'Edit':'Add a'); ?> brand</label>
			<input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value;?>">
			<?php if(isset($_GET['edit'])): ?>
				<a href="brands.php" class="btn btn-info">Cancel</a>
			<?php endif; ?>
			<input type="submit" name="add_submit"  value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> brand" class="btn btn-success">
		</div>
	</form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
	<thead>
		<th></th>
		<th>brand</th>
		<th></th>
	</thead>
 
	<tbody>
	 <?php while($brand1 = mysqli_fetch_assoc($result)): ?> 
	 	<tr>
		<td><a href="Brands.php?edit=<?=$brand1['id']; ?>" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-pencil"></span></a></td>
		<td><?=$brand1['brand']; ?></td>
		<td><a href="Brands.php?delete=<?=$brand1['id']; ?>"  class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
		</tr>
	  <?php endwhile; ?>
	</tbody>

</table>

<?php 
include 'includes/footer.php';

?>