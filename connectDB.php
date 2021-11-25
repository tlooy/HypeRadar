<?php
	require_once("./config.php");	

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if (mysqli_connect_errno()) {
		print('Connect failed');
		print(mysqli_connect_error());
	}
		
	if (!$conn) {
    		die('Could not connect: ' . mysql_error());
	}
	echo 'Connected to database successfully';
	echo "\r\n"
?>
