<?php
include('conn.main.php');
include('class.sendmail.php');
include('validation.php');

//$randpw = gen_rand_pw();
$randpw = 'password';
$newpassword = hash_a_password($randpw);

//validation
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
	 
	//required field validation and security
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	foreach($_POST as $key => $postvalue) {
	$readyvalues[$key] = validation_required($postvalue);	
}

extract($readyvalues);

$errorcount = count($GLOBALS['Error']);
if(($errorcount) == 0) {//if there are no input errors, proceed)

	//upon pressing submit, we run the query
	$checkacct = "SELECT email FROM tenant_login WHERE email = '$email'";
	$result = pg_query($checkacct);
	$num_results = pg_num_rows($result);
	if ($num_results == 0) {
		$nameError = "The email address or company name you entered is invalid";
	}else{
	$pwresetquery = "UPDATE tenant_login set (password) = ('$newpassword') WHERE email = '$email'";
	$pwreset = pg_query($pwresetquery);
	if (!$pwreset) {
		//some error
		} else {
			$success = "Check your email for your temporary password. You then can use it to log in and change your password if desired.";
	//send the email and set a success message
	$subject = "Password Reset Requested";
	$headers = "From: support@appreciateco.com\r\n";
	$headers .="Reply-To: joseph@appreciateco.com\r\n";
	$headers .="Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message ="You have requested to reset your password. If do did not submit this request, please use the temporary password below to log in and reset your password and contact support@appreciateco.com immediately. If you did place this request, please use the temporary password below to log into your account and change your password.\r\n";
	$message .= "<br><br>\r\n";
$message .= "Temporary password:\r\n";
	$message .="$randpw\r\n";
	$message = wordwrap($message, 70, "\r\n");
	mail($email, $subject, $message, $headers);
	}}}}}

//enter the changes made below
//To update the chosen account
?>
<!DOCTYPE HTML PUBLIC "//EN">
<head>
<title>Password Recovery</title>
<link rel="stylesheet" href="css/form.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--to allow responsive css -->
</head>

<body>
	<div id="form" class = "commonform">
	<br>
			<form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
		<label for id = "email">Email used to log in</label><br>
	<input type="text" class = "textinput" name="email" value = "<?php print $email; ?>" size="30" length="30"><br> 
	<input type="submit" class = "submit" name="submit" value="Reset Password">
	</form>	
	<button class = "submit" onclick="closeWin()">Cancel</button> <br>
<?php include('messagecenter.php'); ?>
</div>
</body>
</html>

