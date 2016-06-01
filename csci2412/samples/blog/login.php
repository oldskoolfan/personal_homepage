<!doctype html>
<html>
  <head>
    <title>My Blog</title>
    <script type="text/javascript" src="scripts.js"></script>
    <link type="text/css" href="styles.css" media="screen" rel="stylesheet">
  </head>
  <body>
    <div class="loginform">
        <a href="./">Home</a>
    </div>
    <div id="FormWrapper" class="form-wrapper">
      <h1><center>Welcome to My Blog</center></h1>
      <form action="" method="post">
        <fieldset>
          <legend>Sign In:</legend>
          <label>Username:
            <input type="text" name="username" maxlength="50" />
          </label>
          <label>Password:
            <input type="password" name="password" maxlength="50" />
          </label>
          <input type="submit" value="Submit" />
        </fieldset>
      </form>
      <br />
      <a href="./create-user.php">Create New User</a>
      <br />
      <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          try {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
              throw new Exception("Please enter both username and password");
            }
	    include "mysqli_connect.php";
            $dbc = Blog::getDbConnection();
            $stmt = $dbc->prepare("SELECT UserId, Password, CanEdit FROM users WHERE Username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($id, $hash, $canEdit);
            $stmt->fetch();

            if (password_verify($password, $hash)) {
              session_start();
              $_SESSION['id'] = $id;
              $_SESSION['username'] = $username;
              $_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
              $_SESSION['canEdit'] = $canEdit;
              $location = $canEdit ? "blog.php" : "index.php";
              header("Location: $location");
            }
            else {
              throw new Exception("Login failed");
            }
          }
          catch (Exception $ex) {
             echo '<h4 class="error message">Error: ' .
                $ex->getMessage() . '</h4>';
          }
        }
      ?>
    </div>
  </body>
</html>
