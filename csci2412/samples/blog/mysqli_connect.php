<?php
	set_error_handler("exception_error_handler");
	error_reporting(E_ALL);

	define('CHAR_LIMIT', 250);

	// old way of connecting to MySQL
	//$dbConnection = @mysqli_connect('localhost','root','','blogtest') or die(mysqli_connect_error());

	// object-oriented way -- extend the class
	/*class dbConnection extends mysqli {
		public function __construct($host, $user, $pass, $db) {
			parent::__construct($host, $user, $pass, $db); // basically same as the old way here
			if ($this->connect_error) {			
				die("Houston, we have a problem...($this->connect_errno) $this->connect_error");
			}
			$this->set_charset('utf8');
		}
	}*/

	// might as well put our other custom class here too, since all other files will include this file
	class blog 
	{
		public $id;
		public $title;
		public $body;
		public $moodId;
		public $moodName;
		public $date;
		public $imageId;

		function __construct($title, $body, $moodId) 
		{
			$this->title = $title; 
			$this->body = $body;
			$this->moodId = $moodId;
		}
		public static function getDbConnection() {
		  include "/home/andrew/etc/db-connect.php";
		  $con->select_db('blogdb');
 		  return $con;
		}
	}

	function displayError($err) {	
		echo '<h4 class="error message">Error: ' . $err->getMessage() . '. Occurred in ' .
			$err->getFile() . ' at line ' . $err->getLine() . '.</h4>';
	}

	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline); 
	}

	function postComment() {
		try {
			if ( isset($_POST['comment'], $_POST['blogId']) && !empty($_POST['comment']) && !empty($_POST['blogId']) ) {
	            $userId = $_SESSION['id'];
	            $blogId = $_POST['blogId'];              
	            $body = $_POST['comment'];

	            // check comment length (limit to 250 characters)
	            if (strlen($body) > CHAR_LIMIT) { throw new Exception("Comment must be less than 250 characters"); }

	            //$dbConnection = new dbConnection('localhost', 'root', '', 'blogdb');
	            $dbConnection = Blog::getDbConnection();
		    $stmt = $dbConnection->prepare("INSERT INTO comments(Body, BlogId, UserId) VALUES (?, ?, ?)");
	            $stmt->bind_param('sii', $body, $blogId, $userId);
	            $success = $stmt->execute();
	            if ($success) {
		            return '<h4 class="success message">Comment added successfully!</h4>';
	            }
	            else {
	                throw new Exception($stmt->error);
	            }
	        }
	        else {
	            throw new Exception("Please add your comment before submitting");
	        }
    	}
    	catch (Exception $ex) {
    		throw $ex;
    	}
	}
	$dbConnection = Blog::getDbConnection();
?>
