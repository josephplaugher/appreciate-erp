<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

require 'class/class.validate.php';
require 'class/class.newuser.php';
require 'class/class.subscribe.php';

$validate = new validate();

$c = $validate->optional($_GET['class']);
$m = $validate->optional($_GET['method']);
if($m == 'newUserVerify'){
$cleanvalues['newUserEmail'] = $validate->optional($_GET['email']);//for new user email validation
}

$optional = array('street','city','state','zip','comments','promo','price','discount');
    
foreach($_POST as $key => $value) {
    if(is_array($key)){
        $cleanvalues[$key] = $validate->valArray($value);   
    }else{
    if(in_array($key, $optional) || $c == 'ajaxHTML'){//this allows the use of page updates via ajax without triggering required field problems
	$cleanvalues[$key] = $validate->optional($value);
	}else{
	$cleanvalues[$key] = $validate->required($key,$value);
	}
    }
}
//$cleanvalues['stripeToken'] = $validate->optional($_GET['stripeToken']);
if(($validate->getErrorCount()) == 0) {
$class = new $c();
    switch($m) {
    case 'createAccount':
    case 'processToken':
    case 'newUserInfo':    
    case 'processToken':    
        $class->$m($cleanvalues);
        break;
    case 'newUserVerify':
        $class->$m($cleanvalues['newUserEmail']);
        break;
    }

if(!empty($class->getErrors())) { 
    print json_encode($class->getErrors());
}else{
    $class->setOutputType($c);
    //print 'success: <pre>';
    print $class->returnval();
    //print "</pre>";
}


}else{
    //print 'val errors: ';
    print json_encode($validate->getErrors());
}
/*
print "<br>SESSION: <br><pre>";
print_r($_SESSION);
print "</pre>";

print "<br>customer: <br><pre>";
print_r($class->getTest());
print "</pre>";
 
 */
?>