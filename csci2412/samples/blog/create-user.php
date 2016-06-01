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
      <form action="" method="post" style="width: 415px;">
        <fieldset>
          <legend>Create New User:</legend>
          <label>Username:
            <input type="text" name="username" maxlength="50" />
          </label>
          <br><br>
          <label>Password:
            <input type="password" name="password1" maxlength="50" />
          </label>
          <br><br>
          <label>Confirm Password:
            <input type="password" name="password2" maxlength="50" />
          </label>
          <br><br>
          <input type="submit" value="Submit" />
        </fieldset>
      </form>
      <br />
      <a href="./login.php">Sign In</a>
      <br />
    <?php
      define('COST', 10);
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
          if (empty($_POST['username']) || empty($_POST['password1']) || empty($_POST['password2'])) {
            throw new Exception("All fields are required");
          }
          elseif ($_POST['password1'] !== $_POST['password2']) {
            throw new Exception("Passwords don't match");
          }
          //$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
          //$salt = sprintf("$2a$%02d$", COST) . $salt;
          //$hash = crypt($_POST['password1'], $salt);

          $hash = password_hash($_POST['password1'], PASSWORD_BCRYPT); // don't need our own salt for this example

          //$dbc = new mysqli('localhost', 'root', '', 'blogdb');
	  include "mysqli_connect.php";
	  $dbc = Blog::getDbConnection();

          $checkExistingUserStmt = $dbc->prepare("SELECT * FROM users WHERE Username = ?");
          $checkExistingUserStmt->bind_param('s', $_POST['username']);
          $checkExistingUserStmt->execute();

          if ($checkExistingUserStmt->get_result()->num_rows > 0) {
            throw new Exception('Username already exists. Please choose a different username.');
          }

          $stmt = $dbc->prepare("INSERT INTO users (Username, Password) VALUES (?, ?)");
          $stmt->bind_param('ss', $u, $p);

          $u = $_POST['username'];
          $p = $hash;
          $success = $stmt->execute();
          if ($success) {
            echo '<h4 class="success message">User created successfully!</h4>';
          }
          else {
            throw new Exception($stmt->error);
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
