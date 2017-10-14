<?php
$sql = "SELECT * FROM categories WHERE parent = 0";
$pquery = $dbconn->query($sql);

?>
<!--Top nav bar -->
<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
		  
			<a href="index.php" style="color: white" class="navbar-brand">Collins Boutique</a>
			 <ul class="nav navbar-nav navbar-right">
			 <?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
			  	<?php $parent_id = $parent['id']; 
			  	$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
			  	 $cquery = $dbconn->query($sql2);
			  	 ?>

			 
			  <li class="dropdown hovers">
			  	<a href="#" class="dropdown-toggle" style="color: white" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>

			  	<ul class="dropdown-menu" role="menu">
			  	<?php while ($child = mysqli_fetch_assoc($cquery)) : ?> 
			  		<li class="second"><a href="categories.php?cat=<?=$child['id'];?>"><?php echo $child['category']; ?></a></li>
			  	<?php endwhile; ?>
			  	</ul>
			  </li>


			<?php endwhile; ?>
			<li><a style="color: white" href="cart.php"><span class="glyphicon glyphicon-shopping-cart">Mycart</span></a></li>
			  </ul>

		</div>
	</nav>