<?php
	session_start();	
	session_unset();
	session_destroy();
	setcookie(session_name(),'',0,'/');
   	session_regenerate_id(true);
	pg_close($_SESSION['mainconnection']);
	header ('location: maint.signin.php');
?>
