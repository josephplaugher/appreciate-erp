<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

//set the current date
date_default_timezone_set('America/Los_Angeles');
$_SESSION['today'] = $today = date('Y-m-d');

require_once '../model/class/class.validate.php';
$validate = new validate();

spl_autoload_register(function ($class_name) {
    require_once 'class/class.'.$class_name . '.php';
});

$c = $validate->optional($_GET['class']);
$m = $validate->optional($_GET['method']);
$id = $validate->optional($_GET['id']);

if (isset($_SESSION['company_connect']) ) {
$dbh = new PDO('pgsql:host=localhost;port=5432;dbname='.str_replace(' ','_',$_SESSION['company_connect']).';user=postgres;password=skippy1985');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$empid = $_SESSION['empid'];

    switch($c) {
        case 'home':
            $header = new html();
            $class = new $c();
            $class->$m($header);
            break;
        case 'reportlist':
            //these parameters are for the dataview class, not the input class
            $htm = new html();
            $id = $validate->optional($_GET['id']);
            $payee = $validate->optional($_GET['payee']);

            $class = new $c($htm,$m);
            $class->$m($dbh,$id);//these parameters are for the dataview class, not the input class
            break;
        default:
            $class = new $c();
            $class->$m($dbh,$id);
        }

}else{
    $class = new userview();
    $class->login();
}
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appreciate<?php if($class->getFormTitle()){ print " | ".$class->getFormTitle();}  ?></title><meta http-equiv="Content-Language" content="en-us" />
<link rel="icon" type="image/png" href="logos/AppreciateLogo_1.png">
<link rel="stylesheet" href="css/font.css" />
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/livesearch.css" />
<script src="javascript/jquery.js"></script>
<script src="jQuery_val/dist/jquery.validate.min.js"></script>
<script src="javascript/jquery-ui.js"></script>
<script src="javascript/ajaxSubmit.1.8.js"></script>
<script src="javascript/ajaxcall.1.5.js"></script>
<script src="javascript/form.js"></script>
<?php
if(is_array($class->getCss())){
    foreach($class->getCss() as $css) {
    print '<link rel="stylesheet" href="css/'.$css.'.css" />';
}}
if(is_array($class->getJS())){
    foreach($class->getJS() as $js) {
    print '<script src= "javascript/'. $js .'.js"></script>';
}}
?>
</head>
<body>
<?php
print $class->getView();
?>
</body>
</html>
<?php 


?>