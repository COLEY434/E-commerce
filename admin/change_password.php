<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
if(!is_logged_in()){
	logged_error_redirect('login.php');
}
include 'includes/header.php';

$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$errors = array();
$user_id = $user_data['id'];

?>

<div id="login-form">
<div>
	
	<?php
		if($_POST){
			//form validation
			if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
				$errors[] = "All fields should not be empty";
			}

			//checking password length
			if(strlen($password) < 6 ){
				$errors[] = "Password is too short";
			}
			
			if($password != $confirm){
				$errors[] = "The new password and confirm password does not match";
			}
			if(!password_verify($old_password, $hashed)) {
				$errors[] = "The old password does not exist in our database. ";
			}
		

		//displaying errors
		if(!empty($errors)) {
			echo display_Errors($errors);
		}
		else {
			//update password
			$dbconn->query("UPDATE users SET password = '$new_hashed' WHERE id ='$user_id'");
			$_SESSION['success_flash'] = "Your password has been changed successfully.";
			header("Location: index.php");
		}

}

	?>
</div>
<h2 class="text-center">Change password</h2><hr>
	<form method="post" action="change_password.php">
		<div class="form-group">
			<label for="old_password">Old password</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
		</div>

		<div class="form-group">
			<label for="password">New Password</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>

		<div class="form-group">
			<label for="confirm">Confirm Password</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
		</div>

		<div class="form-group">
			<a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" class="btn btn-primary" value="submit">
		</div>

	</form>
	<p class="text-right"><a href="/E-commerce/index.php">Visit site</a></p>
</div>

<?php 
include 'includes/footer.php';
?>