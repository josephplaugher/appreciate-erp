<?php
include('../session.php');
include('maintsession.php');
include('../access.php');
?>
<!DOCTYPE html PUBLIC "//EN">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--to allow responsive css -->
<title><?php print $_SESSION['company_name'];?></title>
<link rel="stylesheet" href="../css/font.css" />
<link rel="stylesheet" href="../css/header.css" />
<link rel="stylesheet" href="../css/table.css" />
<link rel="stylesheet" href="../css/scroll.css" />
</head>
<body>
<div id = "pagecontainer">
<div id = "header">
<h2>Open Maintenance Requests | <?php print $_SESSION['company_name'];?></h2>
</div>					
<div class="scrollcontainer">
<div class="scroller">
<table>
<tr><th>Submit Date</th><th>Title</th><th>Status</th></tr>
<?php	
	$maintcode = $_SESSION['maintcode'];
	$query = new PDO($logindsn);
	$maint = $query->prepare("SELECT submitdate, title, status, id FROM maint_requests WHERE maintcode = :maintcode ");
	$maint->execute(['maintcode'=>$maintcode]);
	if(!$maint) {
		print "<tr><td colspan = '4'>No open maintenance requests</td></tr>";
		}else{
	foreach($maint as $maintdata) {
	printf("<tr><td>%s</td><td><a href='maintdetails.php?id=%s'>%s</a></td><td>%s</td></tr>", $maintdata['submitdate'],  $maintdata['id'], $maintdata['title'], $maintdata['status']);
	}}
	?>
</table>
</div>
</div>
</div>
</body>
</html>
