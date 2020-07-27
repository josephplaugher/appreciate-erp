<?php 
include('maint.createacctscript.php');
	
?>
<!DOCTYPE html PUBLIC "//EN">
<html>
<head>
<title>Welcome Tenants</title>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/signin.css" />
<link rel="icon" type="image/png" href="../logos/AppreciateLogo_1.png">
</head>
<body>
<div id = "logo-small">
		<img src="../logos/AppreciateLogo_4_H.png" alt="Appreciate Corporation" onclick="home()" style="cursor:pointer"> 	
</div>
<div id = "content" >
<h1>Tenant Center</h1>
<h2>Create An Account</h2> 
<?php include('../messagecenter.php');?>
<form method="POST" action="<?php print htmlspecialchars($_SERVER['PHP_SELF'])?>">
		Enter your email address 			
		<input type="text" class = "textinput" name="email" autofocus="autofocus" value = "<?php print $email?>" size="30" length="30"> <BR>
		Choose a password <BR>
	 	<input type="password" class = "password" name="password" value = "" size="30" length="30">  <BR><br>
		First Name <BR>
		<input type="text" class = "textinput" name="fname" value = "<?php print $fname ?>" size="30" length="30">  <BR>
		Last Name <BR>
		<input type="text" class = "textinput" name="lname" value = "<?php print $lname?>" size="30" length="30">  <BR>
		Street <BR>
		<input type="text" class = "textinput" name="street" value = "<?php print $street?>" size="30" length="30">  <BR>
		City <BR>
		<input type="text" class = "textinput" name="city" value = "<?php print $city?>" size="30" length="30">  <BR>
		State <BR>
		<input type="text" class = "textinput" name="state" value = "<?php print $state?>"  size="30" length="30" maxlength = "2">  <BR>
		Zip <BR>
		<input type="text" class = "textinput" name="zip" value = "<?php print $zip?>"  size="30" length="30" maxlength "10"> <BR><br>
		<input type="submit" class = "submit" name="submit" onclick="validation()" value="Create Account"> <input type="reset" name="reset" class = "submit" value="Reset">
		</form>
</div>
<div id="footer">
Copyright 2016 Appreciate Corporation. All Rights Reserved.
</div>
</body>
</html>
