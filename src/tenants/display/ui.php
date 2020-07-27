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

if (isset($_SESSION['login_user'])) {
$dbh = new PDO('pgsql:host=localhost;port=5432;dbname=tenants;user=postgres;password=skippy1985');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tenantid = $_SESSION['tenantid'];
  

$class = new $c();
$class->$m();

}else{
$class = new $c();
$class->$m();
}
?>
<!DOCTYPE html PUBLIC "//EN">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Center </title>
<meta http-equiv="Content-Language" content="en-us" />
<link rel="icon" type="image/png" href="logos/AppreciateLogo_1.png">
<?php
if($c == 'tenanthome'){
 print '<link rel="stylesheet" href="css/master.css" />';
 print '<link rel="stylesheet" href="css/submaster.css" />';
 }else{
 print '<link rel="stylesheet" href="css/newuser.css" />';
 }
 
?>
<link rel="stylesheet" href="css/font.css" />
<link rel="stylesheet" href="css/form.css" />
<script src="javascript/jquery.js"></script>
<script src="jQuery_val/dist/jquery.validate.min.js"></script>
<script src="javascript/ajaxSubmit.1.8.js"></script>
<script src="javascript/ajaxcall.1.5.js"></script>
<script src="javascript/navigation.js"></script>
<script src="javascript/tenantnav.js"></script>
</head>
<body>
    
<?php        

print $class->getView();

?>
</body>
</html>