<?php include('include/header.php');?>
<h2>Join us, you won't regret it!</h2>
<form action="" method="post">
<fieldset><legend>Create New User</legend>
<label>Username:
<input type="text" name="username">
</label>
<label>Password:
<input type="password" name="pass">
</label>
<label>Confirm:
<input type="password" name="confirm">
</label>
<input type="submit" value="Create Account">
</fieldset>
</form>
<br><br>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include('include/db-connect.php');

		$username = $_POST['username'];
		$pass = $_POST['pass'];
		$confirm = $_POST['confirm'];
		try {
			if (empty($username) ||
				empty($pass) ||
				empty($confirm))
				throw new Exception('Enter all
					fields.');
			if ($pass != $confirm)
				throw new Exception('Passwords must match.');
			
			// create db connection
			//$con = new mysqli('localhost',
			//	'root','','beerstoredb');

			$result = $con->query(
				"SELECT * FROM users 
				WHERE user_name = '$username'");

			if ($result->num_rows > 0) {
				throw new Exception('Username already taken. Please choose another one.');
			}
			// we're good to go, save to db
						
			// encrypt password
			$pass = $con->real_escape_string(
				password_hash($pass, PASSWORD_BCRYPT));
			
			// insert into db
			$query = "INSERT INTO users(
				user_name, pass, join_date)
				VALUES ('$username', '$pass',
					now())";
			if ($con->query($query)) {
				echo 'User created successfully. Click the link below to log in.';
			}
			else {
				echo 'Problem saving to database: ' . $con->error;
			}
		}
		catch (Exception $ex) {
			echo '<div class="error">' . $ex->getMessage() . '</div>';
		}
	}
?>
<br><br>
<a href="login.php">Log In</a>
<?php include('include/footer.html');?>