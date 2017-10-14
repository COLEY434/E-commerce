<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
if (!is_logged_in()){
	logged_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM products WHERE deleted = 1";
$result = $dbconn->query($sql);


//making the product avaliable again
if (isset($_GET['archived'])) {
	$archived_id = (int)$_GET['archived'];
	$sql1 = "UPDATE products SET deleted = 0 WHERE id = $archived_id";
	$dbconn->query($sql1);
	header("Location: archived.php");
}


?>


<h2 class="text-center">Archived products</h2>
<hr>

<table class="table table-condensed table-striped table-bordered">
	<thead>
		<th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th>
	</thead>

	<tbody>
<?php while($archived = mysqli_fetch_assoc($result)) : 
	$childCategory_id = $archived['categories'];
	//getting the category
    $sqlcategories = "SELECT * FROM categories WHERE id = '$childCategory_id'";
	$catResult = $dbconn->query($sqlcategories);
	$childCategory = mysqli_fetch_assoc($catResult);
	$childCat = $childCategory['parent'];

	$sqlcategories2 = "SELECT * FROM categories WHERE id = '$childCat'";
	$catResult2	= $dbconn->query($sqlcategories2);
	$parentCategory = mysqli_fetch_assoc($catResult2);
	
	$category = $parentCategory['category'].'~'.$childCategory['category'];

	
	
	?>
	<tr>
	<td><a class="btn btn-xs btn-info" href="archived.php?archived=<?=$archived['id'];?>"><span class="glyphicon glyphicon-refresh"></span></a></td>
	<td><?=$archived['title'];?></td>
	<td>&#x20A6;<?=$archived['price'];?></td>
	<td><?=$category;?></td>
	<td></td>
	</tr>	
<?php endwhile; ?>
	</tbody>



</table>


<?php
include 'includes/footer.php';

?>