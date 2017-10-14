<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
include 'includes/header.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>
<style>
	body{
		background-image: url(/E-commerce/images/flower.jpeg);
		 height: 100%; 
    	 background-position: center;
   		 background-repeat: no-repeat;
   		 background-size: 100vw 110vh;
	}
</style>
<div id="login-form">
<div>
	
	<?php
		if($_POST){
			//form validation
			if(empty($_POST['email']) || empty($_POST['password'])){
				$errors[] = "You must provide email and password";
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "You must enter a valid email address.";
			}
			//checking password length
			if(strlen($password) < 6 ){
				$errors[] = "Password is too short";
			}
			$query = $dbconn->query("SELECT * FROM users WHERE email = '$email'");
			$users = mysqli_fetch_assoc($query);
			$userCount = mysqli_num_rows($query);

			if($userCount < 1) {
				$errors[] = "user with that email doesn\'t exist";
			}
			if(!password_verify($password, $users['password'])) {
				$errors[] = "Incorrect password.Please try again. ";
			}
		

		//displaying errors
		if(!empty($errors)) {
			echo display_Errors($errors);
		}
		else {
			$user_id = $users['id'];
			login($user_id);
		}

}

	?>
</div>
<h2 class="text-center">Login</h2><hr>
	<form method="post" action="login.php">
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>

		<div class="form-group">
			<input type="submit" class="btn btn-primary" value="submit">
		</div>

	</form>
	<p class="text-right"><a href="/E-commerce/index.php">Visit site</a></p>
</div>

<?php 
include 'includes/footer.php';
?>