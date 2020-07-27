<?php
	//resuming the session began at login
	ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
	session_start();

	$_SESSION['mainconnection'] = pg_pconnect('host=localhost dbname=liquidphase user=postgres password=skippy1985');
		if (!isset($_SESSION['tenantid'])) {
		print pg_last_error(); 
		print "Please log back in.";
		pg_close($connection); // Closing Connection
		pg_close($_SESSION['mainconnection']);//closing connection
		exit();
		}
?>
