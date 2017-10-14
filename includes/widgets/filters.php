<?php
$category_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']):'');
$price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']):'');
$min_price = ((isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']):'');
$max_price = ((isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']):'');
$b = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):'');
$brandQ = $dbconn->query("SELECT * FROM brand ORDER BY brand");
?>
<h3 class="text-center">Search by:</h3>
<h4 class="text-center">Prices</h4>
<form method="post" action="search.php">
	<input type="hidden" name="cat" value="<?=$category_id;?>">
	<input type="hidden" name="price_sort" value="0"> 
	<input type="radio" name="price_sort" value="low"<?=(($price_sort == "low")?' checked': '');?>>LOW TO HIGH<br>
	<input type="radio" name="price_sort" value="high"<?=(($price_sort == "high")?' checked': '');?>>HIGH TO LOW<br>
	<input type="text" name="min_price" class="form-control" placeholder="Min $" value="<?=$min_price;?>">To
	<input type="text" name="max_price" class="form-control" placeholder="Max $" value="<?=$max_price;?>">

<h3 class="text-center">Brand</h3>
  <input type="radio" name="brand" value=""<?=(($b == '')?' checked':'');?>>All<br>
<?php while($brand = mysqli_fetch_assoc($brandQ)) : ?>
	<input type="radio" name="brand" value="<?=$brand['id'];?>"<?=(($b == $brand['id'])?' checked':'');?>><?=$brand['brand'];?><br>
<?php endwhile;?>
<input type="submit" value="search" class="btn btn-primary btn-xs">
</form>