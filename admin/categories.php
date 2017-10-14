<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
if (!is_logged_in()){
	logged_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';
//selecting the parent category for the /add category column
$mainsql = "SELECT * FROM categories WHERE parent = 0";
$mainresult = $dbconn->query($mainsql);
$errors = array();
$category = '';
$post_parent = '';

//edit category
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);
	$sql = "SELECT * FROM categories WHERE id = '$edit_id'";
	$result = $dbconn->query($sql);
	$edit_category = mysqli_fetch_assoc($result);

	
}
//delete category
if (isset($_GET['delete']) && !empty($_GET['delete'])) {

	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$delsql = "SELECT * FROM categories WHERE id = '$delete_id'";
	$delresult = $dbconn->query($delsql);
	$delcategory = mysqli_fetch_assoc($delresult);

     if ($delcategory['parent'] == 0) {
     	$sqlp = "DELETE FROM categories WHERE parent = '$delete_id'";
     	$dbconn->query($sqlp);

     }

     $delsql = "DELETE FROM categories WHERE id = '$delete_id'";
     $dbconn->query($delsql);
     header("Location: categories.php");
}

//process add brand form
 
if (isset($_POST) && !empty($_POST)){
	$category = sanitize($_POST['category']);
	$post_parent = sanitize($_POST['parent']);
    $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
    if (isset($_GET['edit'])) {
    	$id = $edit_id;
    	$sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";
    }
    $formquery = $dbconn->query($sqlform);
    $count = mysqli_num_rows($formquery);

    //check if add category is blank
    if ($category == '') {
    	$errors[] .= 'Please add a category';
    	
    }

    //check if category exists in the database
    if ($count > 0 ){
    	$errors[] .= $category.' already exist. Please choose another category';
    }

    //display erroRs or add to database
    if(!empty($errors)) {
    	//display errors
    	$display = display_Errors($errors); ?>
    	
    	<script>
    	jQuery('document').ready(function(){
    		jQuery('#errors').html('<?=$display; ?>');
    	});
    	</script>
    <?php }
    else{
    	//insert into database
    	$sql = "INSERT INTO categories (parent, category) VALUES ('$post_parent', '$category')";
    	//edit
    	if (isset($_GET['edit'])){
    		$sql = "UPDATE categories SET category = '$category', parent ='$post_parent' WHERE id = '$edit_id'";
    	}
    	$dbconn->query($sql);
    	header("Location: categories.php");
    }
   

}

// displaying the item being edited on the category input 
$category_value = '';
$parent_value = 0;


		if(isset($_GET['edit'])){
			$category_value = $edit_category['category'];
			$parent_value = $edit_category['parent'];

		}
		else
		{
		  if(isset($_POST)) {
				$category_value = $category;
				$parent_value = $post_parent;
			}
		}					

 
	//echo $category_value;
			//echo $parent_value;				
?> 
<h2 class="text-center">Categories</h2>

<hr>
<div class="row">
	<div class="col-md-6">
	<!-- form -->
		<form method="post" class="form" action="Categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>">
		<legend><?=((isset($_GET['edit']))?'Edit':'Add a');?> category</legend>
		<div id="errors"></div>
			<div class="form-group"> 
			   <label for="parent">parent</label>
				<select id="parent" class="form-control" name="parent">
				<option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>parent</option>
					<?php while($mainparent = mysqli_fetch_assoc($mainresult)) : ?>
						<option value="<?=$mainparent['id'];?>"<?=(($parent_value == $mainparent['id'])?' selected="selected"' : '');?>><?=$mainparent['category'];?>
					<?php endwhile; ?>
				</select>
			</div>

				<div class="form-group">

					<label for="category">category</label>
					<input type="text" class="form-control" value="<?=$category_value; ?>" id="category" name="category">
				</div>
					<div class="form-group">
						<input type="submit" name="add" value="<?=((isset($_GET['edit']))?'Edit':'Add a');?> category" class="btn btn-success">
					</div>

		</form>
	</div>

	<!-- category table -->
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<th>Category</th><th>parent</th><th></th>
			</thead>
			<tbody>
			<!-- -->
			<?php 
             //selecting the parent category for display on table
            $sql1 = "SELECT * FROM categories WHERE parent = 0";
            $result1 = $dbconn->query($sql1);
             //looping the parent category 
			while($parent1 = mysqli_fetch_assoc($result1)): ?>
			
				<tr class="bg-primary">
					<td><?=$parent1['category']; ?></td>
					<td>parent</td>
					<td>
						<a href="categories.php?edit=<?=$parent1['id']; ?>" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="categories.php?delete=<?=$parent1['id']; ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>
				<?php
			    $parent_id = $parent1['id'];
			    //selecting the child category of a parent category
			    $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'"; 
			    $childresult = $dbconn->query($sql2);
			    ?>
			<!-- loopin the child category -->
				<?php while ($child = mysqli_fetch_assoc($childresult)): ?>
					
				
				<tr class="bg-info">
					<td><?=$child['category']; ?></td>
					<td><?=$parent1['category']; ?></td>
					<td>
						<a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="categories.php?delete=<?=$child['id']; ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>
			<?php endwhile; ?>
			<?php endwhile; ?>	
			</tbody>
		</table>
	</div>
</div>
<?php
include 'includes/footer.php';

?>