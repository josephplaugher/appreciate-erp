<?php
include('validation.php');

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();//starting session

// Establishing Connection with database Server 
$connection = pg_connect('host=localhost dbname=liquidphase user=postgres password=skippy1985');

//required field validation and security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	foreach($_POST as $key => $postvalue) {
	if($key == 'email') {validate_email($postvalue);}
	$readyvalues[$key] = validation_required($postvalue);	
}

extract($readyvalues);

//check if the user account already exists
$checkexisting = "SELECT email FROM tenant_login WHERE email = '$email'";
$existingresult = pg_query($checkexisting);
$num_results = pg_num_rows($existingresult);
if ($num_results !== 0) {
	account_already_exists('maint.recovery.php');//sets an error to stop the script
}


$errorcount = count($GLOBALS['Error']);
if(($errorcount) == 0) {

	$password = hash_a_password($password);

	// Create the new users entry in the main customer database
	$newuser_query = "INSERT INTO tenant_login (tenantid, email, password, lname, fname, city, street, state, zip, status) VALUES (DEFAULT, '$email', '$password', '$lname', '$fname', 		'$city', '$street', '$state', '$zip', 'pending') RETURNING tenantID";
		$newuresult = pg_query($newuser_query);		
		if (!$newuresult) { 
		print "Problem with new user query " . $newuser_query . "<br/>"; 
	   	print pg_last_error();
		} elseif($newuresult) {
		while($tenantid = pg_fetch_assoc($newuresult)) {//grab the new tenant id
		$_SESSION["tenantid"] = $tenantid['tenantid'];
		}
			if(isset($_SESSION["tenantid"])) {
			header("location: maint.profile.php");
			}
		}
}
}
?>
