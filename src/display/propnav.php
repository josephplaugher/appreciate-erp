<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

require_once '../model/class/class.validate.php';
$validate = new validate();

require_once 'class/class.propNav.php';

$c = $validate->optional($_GET['class']);
$m = $validate->optional($_GET['method']);
 
$table = new table();

$class = new $c();
$class->$m($dbh,$table,$id);
            
print $class->getView();
?>

