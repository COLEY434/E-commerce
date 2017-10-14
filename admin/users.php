<?php
require_once '../core/connect.php';
if (!is_logged_in()){
	logged_error_redirect();
}

if (!has_permission('admin')) {
	permissions_errors_redirect('index.php');
}
include 'includes/header.php';
include 'includes/navigation.php';

//deleteing the user
if (isset($_GET['delete'])){
	$delete_id = sanitize($_GET['delete']);
	$dbconn->query("DELETE FROM users WHERE id = '$delete_id'");
	$_SESSION['success_flash'] = "User has been deleted successfully";
	header("Location: users.php");
}

if(isset($_GET['add'])) { 

	$name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
	$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
	$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
	$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
	$permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
	$errors = array();

if($_POST){

	$emailQuery = $dbconn->query("SELECT * FROM users WHERE email = '$email'");
	$emailcount = mysqli_num_rows($emailQuery);

	if ($emailcount != 0){
		$errors[] = "User with that email already exists in the database.";
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "You must enter a valid email address.";
			}
			//checking password length
	if(strlen($password) < 6 ){
				$errors[] = "Password is too short";
	}

	if($password != $confirm){
		$errors[] = "The two password does not match.";
	}

	$required = array('name','email','password','confirm','permissions');
	foreach ($required as $f) {
		if(empty($_POST[$f])){
			$errors[] = "All fields must be filled";
			break;
		}
	}

		if(!empty($errors)){
			echo display_Errors($errors);
		}else{
			//add user to the database
			$hashed = password_hash($password, PASSWORD_DEFAULT);
			$dbconn->query("INSERT INTO users (full_name,email,password,permissions) VALUES ('$name','$email','$hashed','$permissions')");
			$_SESSION['success_flash'] = "User has been registered successfully";
			header("Location: users.php");

		}
	


}


	?>
	<h2 class="text-center">Add a new user</h2><hr>

	<form method= "post" action="users.php?add=1">
		<div class="form-group col-md-6">
			<label for="name">Full Name:</label>
			<input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="name">Confirm Password:</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="name">Permissions:</label>
			<select class="form-control" name="permissions">
				<option value=""<?=(($permissions = '')?' selected':'');?>></option>
				<option value="editor"<?=(($permissions == 'editor')?' selected':'');?>>Editor</option>
				<option value="admin,editor"<?=(($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
			</select>
		</div>

		<div class="form-group col-md-3">
			<a href="users.php" class="btn btn-info">Cancel</a>
			<input type="submit" value="Add user" class="btn btn-success">
		</div>



	</form>


<?php }
else
{
$userQuery = $dbconn->query("SELECT * FROM users ORDER BY full_name");
?>
<h2 class="text-center">Users</h2><hr>
<a href="users.php?add=1" class="btn btn-success pull-right" style="margin-top: -75px">Add a new user</a>

<table class="table table-condensed table-striped table-bordered">
	<thead>
		<th></th><th>Name</th><th>Email</th><th>Join date</th><th>last Login</th><th>Permissions</th>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($userQuery)) : ?>
			<tr>
				<td>
					<?php if($user['id'] != $user_data['id']) : ?>
						<a href="users.php?delete=<?=$user['id'];?>"" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a>
					<?php endif;?>
				</td>
				<td><?=$user['full_name'];?></td>
				<td><?=$user['email'];?></td>
				<td><?=preety_time($user['date_joined']);?></td>
				<td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'never':preety_time($user['last_login']));?></td>
				<td><?=$user['permissions'];?></td>
			
			</tr>
		<?php endwhile;?>
	</tbody>
</table>

<?php 
include 'includes/footer.php';
}
?>