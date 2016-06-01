<?php 
	// set custom error handler to throw exceptions
	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	set_error_handler("exception_error_handler");

	try {
		session_start(); 
	}
	catch (Exception $ex) { } // there may not be a session to start so we have to us a try block here
?>

<!doctype html>
<html>
<head>
	<title>Andrew's Beer Store</title>
	<link rel="stylesheet" href="include/styles.css">
</head>
<body>
	<div id="PageWrapper">
		<h1>Andrew's Online Beer Warehouse</h1>