<?php
//connect to maintenance database
$logindsn = "pgsql:host=localhost;port=5432;dbname='liquidphase';user=postgres;password=skippy1985";
try{
	// create a PostgreSQL database connection
	$conn = new PDO($logindsn);
}catch (PDOException $e){
	// report error message
	print "Error with login connection: ".$e->getMessage();
}
?>
