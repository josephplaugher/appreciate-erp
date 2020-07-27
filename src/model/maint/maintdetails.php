<?php
include('../session.php');
include('maintsession.php');
include('../access.php');
include('../validation.php');

	$id = pg_escape_string($_GET['id']);
	if($acctno !== ''){
	$_SESSION['id'] = $id;
	}

	$maintcode = $_SESSION['maintcode'];
	$query = new PDO($logindsn);
	$maint = $query->prepare("SELECT submitdate, title, category, description FROM maint_requests WHERE maintcode = :maintcode ");
	$maint->execute(['maintcode'=>$maintcode]);
	foreach($maint as $maintdata) {
	$requestdate = $maintdata['submitdate'];
	$email = $email['email'];
	$title = $maintdata['title'];
	$category = $maintdata['category'];
	$description = $maintdata['description'];
	}

//validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	foreach($_POST as $key => $postvalue) {
	$readyvalues[$key] = validation_required($postvalue);
	}
	
	$errorcount = count($GLOBALS['Error']);
	if(($errorcount) == 0) {
	//upon pressing submit, we run the query
	extract($readyvalues);
	//$newacctquery = "UPDATE sys_coa SET (acctname, description, type, cashyn) = ('$acctname', '$description', '$type', '$cashyn') WHERE acctno = $acctno";
	//$result = pg_query($newacctquery); 
	if($result) {
		$success = "Account updated successfully";
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
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--to allow responsive css -->
<title><?php print $_SESSION['company_name']." | View Account";?></title>
<link rel="stylesheet" href="../css/font.css" />
<link rel="stylesheet" href="../css/form.css" />
<script src="../javascript/closewindow.js"></script>
<script>
	function goback() {
	window.location = "maintreq.php";
	}
</script>
</head>
<body>
<div class = "commonform">
	<h2>Maintenance Request</h2>
	<?php include('../messagecenter.php'); ?>
	<form action="<?php print htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
	<label for id = "request date">Request Date</label><br> 
	<input type="text" class = "halftextinput" name="request date" id = "request date" size="30" length="30" value = "<?php print $requestdate ?>" ><br> 
	<label for id = "email">Email</label><br> 
	<input type="text" class = "textinput" name="email" id = "email" size="30" length="30" value = "<?php print $email ?>" ><br> 
	<label for id = "title">Title</label> <br>
	<input type="textinput" class = "textinput" name="title" id = "title" size="30" length="30" value = "<?php print $title ?>"><br>
	<label for id = "category">Category</label><br>
	<select name = "category" class = "select-wide">
			<option value= "appliance" <?php if($category=="appliance"){print "selected";} ?>>Appliance</option>  
			<option value= "electrical" <?php if($category=="electrical"){print "selected";} ?>>Electrical</option>  
			<option value= "flooring" <?php if($category=="flooring"){print "selected";} ?>>Flooring</option>  
			<option value= "outdoors" <?php if($category=="outdoors"){print "selected";} ?>>Outdoors</option>  			
			<option value= "paint" <?php if($category=="paint"){print "selected";} ?>>Paint</option>  
			<option value= "plumbing" <?php if($category=="plumbing"){print "selected";} ?>>Plumbing</option>  
			<option value= "roof" <?php if($category=="roof"){print "selected";} ?>>Roof</option>  
	</select><br><br>
	<textarea name="description"  rows="8" columns="12" readonly><?php print $description?></textarea><BR>
	<input type="submit" class = "submit" id = "complete" name="marke" value="Save Changes">	
	<input class = "submit" type ="button" onclick="goback()" value = "Back">
	<input class = "submit" type ="button" onclick="closeWin()" value = "Close">
	</form>
</div>
</body>
</html>

