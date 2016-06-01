<?php include('include/header.php');?>
<h2>Log In to get some good beer!</h2>
<form action="" method="post">
<fieldset><legend>Sign In</legend>
<label>Username:
<input type="text" name="username">
</label>
<label>Password:
<input type="password" name="pass">
</label>
<input type="submit" value="Log In">
</fieldset>
</form>
<br><br>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
		include('include/db-connect.php');

		$username = $_POST['username'];
		$pass = $_POST['pass'];
		try {
			if (empty($username) ||
				empty($pass))
				throw new Exception('Enter all
					fields.');			
			
			// we're good to go				
			// SELECT from db
			$query = "SELECT * FROM users
				WHERE user_name = '$username'";
			$result = $con->query($query);

			if (!$result)
				throw new Exception("Login failed. Please try again.");

			$row = $result->fetch_assoc();
			$id = $row['user_id'];
			$hash = $row['pass'];
			$username = $row['user_name'];
			$isAdmin = $row['is_admin'];
			
			// check password
			if (password_verify($pass, $hash)) {
				// success! start session and 
				// redirect to main page
			  session_destroy();
              session_start();
              $_SESSION['id'] = $id;
              $_SESSION['username'] = $username;
              $_SESSION['edit'] = false;
              $_SESSION['isAdmin'] = $isAdmin;
              header("Location: index.php");
            }
            else {
              throw new Exception("Login failed. Please try again.");
            }
		}
		catch (Exception $ex) {
			echo '<div class="error">' . $ex->getMessage() . '</div>';
		}
	}
?>
<br><br>
<a href="new_user.php">Create New User</a>
<?php include('include/footer.html');