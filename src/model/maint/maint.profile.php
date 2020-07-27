<?php
include('maint.session.php');
include('../validation.php');

$tenantid = $_SESSION['tenantid'];
$email = $_SESSION['login_session'];
$lname = $_SESSION['lname'];
$lname = $_SESSION['fname'];
$street = $_SESSION['street'];
$state = $_SESSION['state'];
$zip = $_SESSION['zip'];

//****trying to decide exactly how the property manager will know who is submitting maintenance requests****

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
foreach($_POST as $key => $postvalue) {
$readyvalues[$key] = validation_required($postvalue);
}

extract($readyvalues);
$errorcount = count($GLOBALS['Error']);
if(($errorcount) == 0) {

$insertquery = "INSERT INTO maint_requests (email, submitdate, title, status, category, description, tenantid, id) VALUES ('$email' , DEFAULT, '$title', 'pending', '$category', '$description', $tenantid, DEFAULT)";
	$result = pg_query($insertquery); 
	if($result) {
	$success = "We have received your request";
	}elseif (!$result) { 
		$errormessage = pg_last_error(); 
		print "Error with query: " . $errormessage; 
		pg_close();
	}
	}
}

?>
<!DOCTYPE html PUBLIC "//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>Maint Request</title>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--to allow responsive css -->
<link rel="stylesheet" media="screen and (max-width: 900px)" href="../css/m.master.css" />
<link rel="stylesheet" media="screen and (min-width: 901px)" href= "../css/maint.master.css" />
<link rel="stylesheet" href="../css/maint.css" />
<link rel="icon" type="../image/png" href="../logos/AppreciateLogo_1.png">
<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "90%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>
<!-- This includes the functions that controll all our links -->
<script src="../javascript/mainlinks.js">
</script>
<script src="../javascript/sublinks.js">
</script>	
</head>
<body link="white">
<div id="mainpage-container">
	<div id= "appreciate">
	<p><img src = "../logos/AppreciateLogo_4_H.png" alt = "Appreciate Corporation"></p>
	</div>	
	<div id="mainco-name-small">
		<p1><form action="maint.logout.php" method="post"> 
		<input type="submit" class = "logout" name="logout" value="Log Out"> 
		<input type="button" class = "logout" onclick="openNav()" id="menubutton" value="Menu"> 
		</form>
	</div>
	<div id="maintop-nav">
	<h1><?php print "Welcome ".$_SESSION['fname']."!";?></h1>
	
	</div>
	<div id = "mySidenav" class = "sidenav">
    	<span class="closebutton"><a href="javascript:void(0)"  onclick="closeNav()"><input type="button" class = "submit" name="close" value="Close"></a></span>
	<input type = "button" class = "mainlinks" onclick="newrequest();closeNav()" style="cursor:pointer" value = "Submit New Request"> <br>
	<input type = "button" class = "mainlinks" onclick="requesthistory();closeNav()" style="cursor:pointer" value = "Request Status"> 
	</div>

	<div id="maincontent"></div>
	<div id="footer">
		
	</div>
</div>
</body>
</html>

