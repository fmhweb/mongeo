<?php
	//$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/config/mysql.ini");
	$ini = parse_ini_file("/data/web/05_mongeo/config/mysql.ini");
	$mysqli = new mysqli($ini['host'], $ini['user'], $ini['pass'], $ini['db']);
	if($mysqli->connect_errno){
		echo "Sorry, this website is experiencing problems.";

		echo "TEST: Error: Failed to make a MySQL connection, here is why: \n";
		echo "Errno: " . $mysqli->connect_errno . "\n";
		echo "Error: " . $mysqli->connect_error . "\n";

		exit;
	}
?>
