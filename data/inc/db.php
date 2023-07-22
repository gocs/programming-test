<?php

function SQLConnection( $dbHost, $dbUser, $dbPwd, $dbName ){
	$conn = new mysqli($dbHost, $dbUser, $dbPwd, $dbName);

	// Check connection
	if ($conn->connect_error) {	
	  die("Connection failed: " . $conn->connect_error);
	}
	
	return $conn;
}