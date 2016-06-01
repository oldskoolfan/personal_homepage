<?php
require('mysqli_connect.php');

// define some constants
define('SAVE', 'save');
define('EDIT', 'edit');
define('DELETE', 'delete');
define('COMMENT', 'Add Comment');

$imgTypes =
[
	'image/jpeg',
	'image/JPG',
	'image/pjpeg',
	'image/png',
	'image/PNG',
	'image/X-PNG',
	'image/x-png',
	'image/gif'
];

// check if form was submitted by looking at the server request type
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	try {
		// get our action command

		// are we posting a new comment?
		if ( isset($_POST['submit']) && $_POST['submit'] === COMMENT ) {
			echo postComment();
		}
		// or are we deleting a comment?
		elseif ( isset($_POST['submit']) && strtolower($_POST['submit']) === DELETE && !empty($_POST['commentId']) ) {
			$commentId = $_POST['commentId'];
			$dbConnection = Blog::getDbConnection();
			$id = $_POST['commentId'];
			$result = $dbConnection->query("DELETE FROM comments WHERE CommentId = $commentId");
			if ($result) {
				echo '<h4 class="success message">Comment deleted successfully!</h4>';
			}
			else {
				throw new Exception($dbConnection->error);
			}
		}
		// or are we adding, editing, or deleting a blog?
		elseif ( !empty($_POST['action']) ) {
			$action = $_POST['action'];

			$dbConnection = Blog::getDbConnection();

			switch ($action) {
				case SAVE:

					$imageId = 0; // initialize variable

					// validate that all values were entered
					if ( empty($_POST['title']) || empty($_POST['body']) || empty($_POST['mood']) ) {
						throw new Exception("Not all fields are entered");
					}

					// validate file if there is one
					if (file_exists($_FILES['upload']['tmp_name'])) {
						// get temp name and file name
						$tmpName = $_FILES['upload']['tmp_name'];
						$fileName = $_FILES['upload']['name'];

						// check file type
						$fileInfo = finfo_open(FILEINFO_MIME_TYPE);

						if ( in_array(finfo_file($fileInfo, $tmpName), $imgTypes) ) {
							// read image data (turns into a string of bytes)
							$handler = fopen($tmpName, 'r'); // opens image file stream
							$data = fread($handler, filesize($tmpName)); // read byte data
							fclose($handler); // close image file stream

							$data = $dbConnection->real_escape_string($data);
							$query = "INSERT INTO images (FileName, ImageData) VALUES('$fileName', '$data')";
							$result = $dbConnection->query($query);
							if ( !$result ) {
								throw new Exception($dbConnection->error);
							}
							$query = "SELECT ImageId FROM images ORDER BY DateCreated DESC LIMIT 1";
							$result = $dbConnection->query($query);
							$imageIds = $result->fetch_assoc();
							$imageId = $imageIds['ImageId'];
						}
						else {
							throw new Exception("Please upload a valid image file");
						}
						if ($_FILES['upload']['error'] > 0) { // check for upload errors
							throw new Exception("There were problems uploading the file. Error Code: " .
								$_FILES['upload']['error']);
						}

						if ( file_exists($tmpName) && is_file($tmpName) ) { // delete temp file if still exists
							unlink($tmpName);
						}
					}

					// initialize a blog object
					$blog = new blog(
						$dbConnection->real_escape_string($_POST['title']), // need this because of quotes in SQL
						$dbConnection->real_escape_string($_POST['body']),
						$_POST['mood']
					);

					if ($imageId > 0 ) { $blog->imageId = $imageId; }

					// check if we are creating a new blog or editing an existing one
					if ( !empty($_POST['id']) ) {

						// if updating, set id and do SQL update query
						$blog->id = $_POST['id'];
						$query = $imageId > 0 ? getUpdateQuery(true, $blog) : getUpdateQuery(false, $blog);
					}
					else {
						// new blog; do SQL insert query
						$query = $imageId > 0 ? getInsertQuery(true, $blog) : getInsertQuery(false, $blog);
					}
					$result = $dbConnection->query($query); // execute query, get result

					if ($result) {
						$_POST['id'] = ''; // clear selected blog id
						echo '<h4 class="success message">Blog saved successfully!</h4>';
					}
					else {
						throw new Exception($dbConnection->error); // must have a MySQL error to display
					}
					break;
				// END case SAVE
				case EDIT:

					// check to make sure we have an id
					$id = isset($_POST['id']) ? $_POST['id'] : '';

					// write our select query
					$query = <<< EDITQUERY
						SELECT
							b.BlogId,
							b.Title,
							b.Body,
							b.MoodId
						FROM Blogs b
						WHERE b.BlogId = $id
EDITQUERY;
					// excecute the query
					$result = $dbConnection->query($query);

					// should only contain one row
					while ($row = $result->fetch_assoc()) {

						// create a blog object
						$blog = new blog($row['Title'], $row['Body'], $row['MoodId']);
					}

					// set post values to fill form
					$_POST['title'] = $blog->title;
					$_POST['body'] = $blog->body;
					$_POST['mood'] = $blog->moodId;
					break;
				// END case EDIT
				case DELETE:
					// check to make sure we have an id
					$id = isset($_POST['id']) ? $_POST['id'] : '';

					// write our delete query
					$query = "DELETE FROM Blogs WHERE BlogId = $id";

					// execute the query
					$dbConnection->query($query);
					if ($dbConnection->affected_rows == 1) {
						echo '<h4 class="success message">Blog deleted successfully!</h4>';
					}
					else {
						throw new Exception("Problem deleting blog");
					}
					break;
				// END case DELETE
			} // end SWITCH
		} // end IF
	} // end TRY
	catch(Exception $ex ) {
		displayError($ex);
	}
} // end IF 'POST'

function getInsertQuery($hasImage, $blog) {
	if ($hasImage) {
		$query = <<< INSERTTRUE
			INSERT INTO Blogs(Title, Body, CreatedDate, MoodId, ImageId)
			VALUES (
				'$blog->title',
				'$blog->body',
				NOW(),
				$blog->moodId,
				$blog->imageId
			)
INSERTTRUE;
	}
	else {
		$query = <<< INSERTFALSE
			INSERT INTO Blogs(Title, Body, CreatedDate, MoodId)
			VALUES (
				'$blog->title',
				'$blog->body',
				NOW(),
				$blog->moodId
			)
INSERTFALSE;
	}
	return $query;
}

function getUpdateQuery($hasImage, $blog) {
	if ($hasImage) {
		$query = <<< UPDATETRUE
			UPDATE Blogs
			SET
				Title = '$blog->title',
				Body = '$blog->body',
				MoodId = '$blog->moodId',
				ImageId = $blog->imageId
			WHERE
				BlogId = $blog->id
UPDATETRUE;
	}
	else {
		$query = <<< UPDATEFALSE
			UPDATE Blogs
			SET
				Title = '$blog->title',
				Body = '$blog->body',
				MoodId = '$blog->moodId'
			WHERE
				BlogId = $blog->id
UPDATEFALSE;
	}
	return $query;
}

?>
