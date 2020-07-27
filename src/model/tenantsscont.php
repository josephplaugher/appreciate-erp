<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

spl_autoload_register(function ($class_name) {
    require_once 'class/class.'.$class_name . '.php';
});

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

if(isset($_SESSION['company_connect'])){
$dbh = new PDO('pgsql:host=localhost;port=5432;dbname=tenants;user=postgres;password=skippy1985');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$validate = new validate();

$optional = array('street','city','state','zip','comments','subtype','docno',
                    'description','lastrecbal','clearedbal','balDiff','entity','property','unit');
    
foreach($_POST as $key => $value) {
    if(is_array($key)){
        $cleanvalues[$key] = $validate->valArray($value);   
    }else{
    if(in_array($key, $optional)){
	$cleanvalues[$key] = $validate->optional($value);
	}else{
	$cleanvalues[$key] = $validate->required($value);
	}
    }
}

if(($validate->getErrorCount()) == 0) {
       
//just for testing: these will be POST values normally    
unset($cleanvalues['class']);
unset($cleanvalues['method']);

    $c = $_GET['class'];
    $m = $_GET['method'];
    $cleanvalues['empid'] = $_SESSION['empid'];

//print "the inputs <pre>";
//print_r($cleanvalues);
//print "</pre>";

    switch($c){
        case 'adduser':
        $useraccount = new useraccount();
        $class = new adduser($cleanvalues,$dbh,$useraccount);
        break;
    
        default:
        $class = new $c();
        $method = $m;
        $class->$method($cleanvalues,$dbh);
        break;
    }
 
    if(!empty($class->getErrors())) {  
        //print 'error: ';
        print json_encode($class->getErrors());
    }else{
        $class->setOutputType($method);
        //print 'success: <pre>';
        print $class->returnval();
        //print "</pre>";
    }
}else{
    //print 'val errors: ';
    print json_encode($validate->getErrors());
} 
//print "<br>balance: <br>";
//print_r($class->bal());
//print '<br>login user: '.$_SESSION['login_user'] ;
//print_r($_SESSION);
//print "<br>docroot: ". $_SERVER['DOCUMENT_ROOT'];
?>