<?php
    // display blogs
    $query = <<< QUERY
        SELECT 
            b.BlogId, 
            b.Title, 
            b.Body, 
            m.MoodId, 
            m.MoodName, 
            m.MoodDescription, 
            b.CreatedDate,
            b.ImageId
        FROM blogs b 
        JOIN moods m ON b.MoodId = m.MoodId 
        ORDER BY b.CreatedDate DESC
QUERY;
    
    // execute query and set result to variable
	$dbConnection = Blog::getDbConnection();
    $stmt = $dbConnection->prepare($query);
    $stmt->execute();
    $blogs = $stmt->get_result();

    if ($blogs) {

		if ( $isEditPage && !empty($selectedBlogId) ) {
            $selectedBlog = array();
            $nonSelectedBlogs = array();
            foreach ($blogs as $row) {
              if ($row['BlogId'] == $selectedBlogId) {
                array_push($selectedBlog, $row);
              }
              else {
                array_push($nonSelectedBlogs, $row);
              }
            }
            $blogs = array_merge($selectedBlog, $nonSelectedBlogs);
        }

      foreach ($blogs as $row) {
          // for each row, create a new blog object and set all values we need
          $blog = new blog($row['Title'], $row['Body'], $row['MoodId']);
          $blog->Id = $row['BlogId'];
          $blog->moodName = $row['MoodName'];
          $blog->date = date_format(date_create($row['CreatedDate']), "l, F d, Y \a\\t g:ia"); // format the date
          $blog->imageId = $row['ImageId'];
          $body = nl2br($blog->body); // here we need to add HTML line breaks to the body text
          if ($isEditPage) {
          	$class = $blog->Id == $selectedBlogId ? 'blog selected' : 'blog';
          }
          else {
          	$class = 'blog';
          }
          $desc = $row['MoodDescription']; // we can get the mood description and show it when someone hovers over the mood
          if ( isset($blog->imageId) ) {
             $img = <<< IMGHTML
              <div class="img-wrapper">
                <img src="get-image.php?image=$blog->imageId"/>
              </div>
IMGHTML;
          }
          else {
              $img = '';
          }

          // get comments if any
          $comments = $dbConnection->query(
            "SELECT c.CommentId, c.Body, u.Username, c.CreatedDate FROM comments c " . 
            "JOIN users u ON c.UserId = u.UserId WHERE c.BlogId = $blog->Id ORDER BY c.CreatedDate"
          );
          $commentText = '';
          if ($comments && $comments->num_rows > 0) {
            $commentText = '<b>Comments:</b><br />';
            foreach($comments as $comment) {
              $commentId = $comment['CommentId'];
              $userName = $comment['Username'];
              $commentBody = $comment['Body'];
              $commentDate = date_format(date_create($comment['CreatedDate']), "l, F d, Y \a\\t g:ia");
              if ($isEditPage) {
              	$commentButton = 
              		'<input class="blog-button" type="submit" name="submit" value="Delete" ' . 
              		'onclick="return deleteComment(' . $commentId . ');"/> ';
              }
              else {
              	$commentButton = '';
              }
              $commentText .= <<< COMMENTTEXT
                <div class="comment">
                  <div class="comment-title">Posted by $userName on $commentDate</div>$commentButton
                  <div class="comment-body">$commentBody</div>
                </div>
COMMENTTEXT;
            }
          }

          if ( isset($_SESSION['agent'], $_SESSION['id'], $_SESSION['username']) && 
          $_SESSION['agent'] === md5($_SERVER['HTTP_USER_AGENT']) ) {
            $userName = $_SESSION['username'];
            $commentForm = <<< COMMENTFORM
                <input type="hidden" name="blogId" value="$blog->Id" />
                <input id="CommentIdTextBox" type="hidden" name="commentId" />
                <textarea name="comment" rows=3 cols=92 placeholder="Add your comment here, $userName!"></textarea>
                <div class="button-div">
                  <input type="submit" name="submit" value="Add Comment" />
                </div>              
COMMENTFORM;
          }
          else {
            $commentForm = '';
          }

          if ($isEditPage && $canEdit) {
          	$buttons = 
          		'<input class="blog-button" type="button" value="Delete" name="delete" ' . 
          		'onclick="setAction(this.name, '. $blog->Id . ');" />' .
                '<input class="blog-button" type="button" value="Edit" name="edit" ' . 
                'onclick="setAction(this.name, ' . $blog->Id . ');" />';
          }
          else {
          	$buttons = '';
          }
          // put the blog info into HTML on the page
          echo <<< BLOG
              <div id="Blog$blog->Id" name='blog' class="$class">
                <div class="blog-section">
                  <div class="title">$blog->title</div>
                  $buttons                    
                  <div class="date">Posted on $blog->date</div>
                  <div class="mood" title="$desc">Mood: $blog->moodName</div>
                </div>
                <div class="blog-section">$img</div>
                <div class="blog-section">
                  <div class="body">$body</div>
                </div>
                <form action="" method="POST">
	                <div class="blog-section">
	                  <div class="comment-section">$commentText</div>
	                </div>
	                <div class="blog-section">
	                  $commentForm    
	                </div>
                </form>
              </div>
BLOG;
      }
    }
?>
