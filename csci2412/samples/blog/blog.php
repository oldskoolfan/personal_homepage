<?php
  session_start();
  if ( !isset($_SESSION['agent'], $_SESSION['id'], $_SESSION['username']) || 
  $_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) ) {
    header('Location: login.php'); // if not set or no match, redirect to login page
  }
  else {
    $canEdit = $_SESSION['canEdit'];
    if (!$canEdit) { header('Location: index.php'); }
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
      <h4>You are logged in as <?php echo $_SESSION['username']; ?></h4>
      <a href="./">View Blog</a>&nbsp;&nbsp;&nbsp;<a href="./index.php?logout=1">Log Out</a>
    </div>
    <div id="FormWrapper" class="form-wrapper">
      <?php require("form-handler.php"); ?>
      <form id="MyForm" action="" method="post" enctype="multipart/form-data">
    	  <fieldset>
    	    <legend>My Blog:</legend>
          <input id="BlogId" type="hidden" name="id" 
            value="<?php if (isset($_POST['id'])) { print $_POST['id']; } ?>"
          />
      		<label>Title: 
          <input id="Title" name="title" type="text" maxlength="50" 
            value="<?php if (isset($_POST['title'])) { print $_POST['title']; } ?>"
          /></label>
      		<label>Today I'm Feeling:
      		  <select id="Mood" name="mood">
              <option></option>
              <?php
                //$dbConnection = new dbConnection('localhost', 'root', '', 'blogdb');
                $dbConnection = Blog::getDbConnection();
		$query = "SELECT * FROM Moods";
                $moods = $dbConnection->query($query);

                foreach ($moods as $row) {
                    if (isset($_POST['mood']) && $row['MoodId'] == $_POST['mood']) {
                        echo "<option selected value='" . $row['MoodId'] . "'>" . $row['MoodName'] . "</option>";
                    }
                    else {
                        echo "<option value='" . $row['MoodId'] . "'>" . $row['MoodName'] . "</option>";
                    }
                }
                $dbConnection->close();
              ?>
      		  </select>
          </label>
          <br />
          <textarea id="Body" rows=10 cols=85 name="body"><?php if (isset($_POST['body'])) { print $_POST['body']; }?></textarea>          
          <br />
          <br />
          <label>Image:
          <input id="UploadImage" name="upload" type="file" />
          </label>
          <br />
          <br />
          <div class="button-div">
            <input type="button" value="Save" name="save" onclick="setAction(this.name);" />
            <input type="button" value="Clear" onclick="clearPage();" />
          </div>
          <input id="ActionTextBox" type="hidden" name="action" />
    	  </fieldset>
    	</form>
      <?php 

        // get selected blog Id if there is one
        $selectedBlogId = isset($_POST['id']) ? $_POST['id'] : '';
        $isEditPage = true;
        include('get-blogs.php');
      ?>
    </div>
    <script type="text/javascript">
        // when page loads, if we have a save success message, we want to clear the form fields
        if (document.getElementsByTagName('h4').length > 1) { 
            // if there's a message, check if it has error class
            if (document.getElementsByTagName('h4')[0].className != 'error') { 
                
                // clear form fields
                document.getElementById('BlogId').value = '';
                document.getElementById('Title').value = '';
                document.getElementById('Body').value = '';
                document.getElementById('Mood').selectedIndex = 0;

                // make sure all blogs are unselected
                var blogs = document.getElementsByName('blog');

                for (i = 0; i < blogs.length; i++) {
                  blogs[i].className = 'blog';
                }
            }
        }
    </script>
  </body>
</html>
