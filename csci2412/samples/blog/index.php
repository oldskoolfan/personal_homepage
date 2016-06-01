<?php
  session_start();
  if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    session_destroy();
    header("Location: index.php");
  }
?>
<!doctype html>
<html>
  <head>
    <title>My Blog</title>
    <script type="text/javascript" src="scripts.js"></script>
    <link type="text/css" href="styles.css" media="screen" rel="stylesheet">
  </head>
  <body>    
    <div class="loginform">
      <?php
        if ( isset($_SESSION['agent'], $_SESSION['id'], $_SESSION['username']) && 
        $_SESSION['agent'] === md5($_SERVER['HTTP_USER_AGENT']) ) {
          $userName = $_SESSION['username'];
          $canEdit = $_SESSION['canEdit'];
          $editLink = $canEdit ? '<a href="./blog.php">Edit Blog</a>&nbsp;&nbsp;&nbsp;' : '';
          echo "<h4>You are logged in as $userName</h4>";
          echo $editLink . '<a href="./index.php?logout=1">Log Out</a>';
        }
        else {
          echo '<a href="./login.php">Log In</a>';
        }
      ?>
    </div>
    <div id="FormWrapper" class="form-wrapper">      
      <h1><center>Welcome to My Blog</center></h1>
      <?php 
        require('mysqli_connect.php');

        // if postback, try and save comment
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
          try {
            echo postComment();
          }
          catch(Exception $ex) {
            displayError($ex);
          }
        }

        $isEditPage = false;
        include('get-blogs.php');
      ?>
    </div>
  </body>
</html>