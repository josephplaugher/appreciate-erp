<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

spl_autoload_register(function ($class_name) {  
    require 'class/class.'.$class_name . '.php';
});

require '../display/class/class.html.php';
require '../display/class/class.formelements.php';
$html = new html(); 
$form = new formelements();

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

if(isset($_SESSION['company_connect'])){
$dbh = new PDO('pgsql:host=localhost;port=5432;dbname='.$_SESSION['company_connect'].';user=postgres;password=skippy1985');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$validate = new validate();

$c = $validate->optional($_GET['class']);
$m = $validate->optional($_GET['method']);
$newUserEmail = $validate->optional($_GET['email']);//for new user email validation
    
$optional = array('email','promo','discount','industry','contact','phone','street','city','state','zip','comments','subtype','docno',
'description','lastrecbal','clearedbal','balDiff','selectedtotal','entity','property','unit',
'prop-id','prop-ein','prop-entityname','prop-shortname','prop-street','prop-city',
'prop-state','prop-zip');
    
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

if(($validate->getErrorCount()) == 0) {
       
    switch($c){
        
        case 'properties':
        $class = new $c();
        $method = $m;
        $class->$method($cleanvalues,$dbh,$html,$form);
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
        $class->setOutputType($c);
        //print 'success: <pre>';
        print $class->returnval();
        //print "</pre>";
    }
}else{
    //print 'val errors: ';
    print json_encode($validate->getErrors());
}
//print "<br>query: <br><pre>";
//print_r($class->getTestData());
//print "<br>prop: <br></pre>";
//print "<br>prop: <br><pre>";
//print_r($class->getTestVal());
//print "<br>prop: <br></pre>";
?>