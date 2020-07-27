	<H2 class = "header">Request History and Status</H2>
	<div class = "scroller">
	<table>
	<col width="auto"><col width="auto"><col width="auto">
	<tr><th>Submit Date</th><th>Title</th><th>Status</th></tr>

	<?php		
	$maint_query = "SELECT submitdate, title, status FROM maint_requests WHERE tenantid = '$tenantid'";
	$maintresult = pg_query($maint_query);
		if(!$maintresult) {
		print "<tr><td colspan = '4'>No open maintenance requests</td></tr>";
		}else{
		while($maintdata = pg_fetch_assoc($maintresult)) {
	printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $maintdata['submitdate'], $maintdata['title'], $maintdata['status']);
	}}
	?>

	</table>
	</div>
<p>Key: <br>"pending" - management has not yet reviewed your request.<br>"in process" - Management has received your request and is making arrangements.<br>"complete" - Your request is fulfilled.<br>"closed" - Management will close your completed request after a period of time. It will be removed from this list.</p>
	</div>