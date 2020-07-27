<?php
include ('maint.loginhash.php'); 
?>
<!DOCTYPE html PUBLIC "//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>Welcome Tenants</title>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/signin.css" />
<link rel="icon" type="image/png" href="../logos/AppreciateLogo_1.png">
<script>
	function recovery() {
	window.location = "recovery.php";
	}

	function newacct() {
	window.location = "maint.index.php";
	}
</script>
</head>
<body>
	<div id = "logo-small">
		<img src="../logos/AppreciateLogo_4_H.png" alt="Appreciate Corporation" onclick="home()" > 	
	</div>
	<div id="content">
		<h1>Tenant Center</h1>
		<h2>Sign In</h2> 
		<?php include('../messagecenter.php');?>
		<form method="POST" action="<?php print htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<label for = "email">Username: </label><br>
		<input type="text" name="email" id ="email" class = "textinput" value = "<?php print $email;?>" size="30" length="30" autofocus> <br><br>
		<label for = "password">Password: </label><br>
		<input type="password" name="password" id="password" class = "password" size="30" length="30"> <br><br>
		<input type="submit" class = "submit" name="submit" value="Submit"><br> 
		
		</form>
		<input type="button" class = "submit" name="recovery" onclick = "recovery()" value="Reset Password"><br> 
		<input type="button" class = "submit" name="creatacct" onclick = "newacct()" value="Create Account">
</div>
<div id="footer">
Copyright 2016 Appreciate Corporation. All Rights Reserved.
</div>
</body>
</html>

