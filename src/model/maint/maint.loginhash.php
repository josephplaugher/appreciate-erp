<?php
include('../validation.php');
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start(); // Starting Session
	
//required field validation and security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	foreach($_POST as $key => $postvalue) {
	$readyvalues[$key] = validation_required($postvalue);	
}

extract($readyvalues);

// Establishing Connection with Server
$connection = pg_connect('host=localhost dbname=liquidphase user=postgres password=skippy1985');

// SQL query to fetch information of registerd users and finds user match.
$query = "SELECT tenantid, email, password, lname, fname, city, street, state, zip, status FROM tenant_login WHERE email =  '$email'";
$loginquery = pg_query($query);	
if($loginquery){
while($pswd = pg_fetch_assoc($loginquery)) {
		$psword = $pswd['password'];
		$_SESSION['tenantid']= $pswd['tenantid'];
		$_SESSION['login_session']= $pswd['email'];
		$_SESSION['lname']= $pswd['lname'];
		$_SESSION['fname']= $pswd['fname'];
		$_SESSION['street'] = $pswd['city'];
		$_SESSION['state'] = $pswd['state'];
		$_SESSION['zip'] = $pswd['zip'];
		$_SESSION['status'] = $pswd['status'];
		}

	}elseif (!$loginquery) { 
	$GLOBALS['Error'][] = "Username or Password is Invalid"; //sets an error if the username does not exist
	}

$checkpassword = check_password($password,$psword); //checks if the entered password is correct, set an error if not
	
$errorcount = count($GLOBALS['Error']);
if(($errorcount) == 0) {//if there are no input errors, proceed)
	header("location: maint.profile.php");
	session_write_close();
	}	
}
?>
