<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

//turn on error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

//set the current date
date_default_timezone_set('America/Los_Angeles');
$_SESSION['today'] = $today = date('Y-m-d');

//new validation instance
require_once '../model/class/class.validate.php';
$validate = new validate();

spl_autoload_register(function ($class_name) {
    require_once 'class/class.'.$class_name . '.php';
});

$c = $validate->optional($_GET['class']);
$m = $validate->optional($_GET['method']);
$resend = $validate->optional($_GET['resend']);

//this block is for stripe
require '../model/stripe/init.php';
require '../model/stripe/appreciateco/keys.php';
\Stripe\Stripe::setApiKey($SECRET_KEY);
$plans = \Stripe\Plan::all(array("limit" => 10));
$i = 0;
foreach($plans->data as $plans){
$plannames[]= $plans->name;
$planprices[]= number_format(($plans->amount/100),2);
$i = $i + 1;
}

$class = new userview();
if (isset($_SESSION['loginemail'])) {
    if($_SESSION['pmtstage']){
        $class->payment();   
        $class->$m($_SESSION,$promo,$plannames,$planprices);
    }else{
        $class->$m($_SESSION,$promo,$plannames,$planprices,$resend);
    }
}else{
    $class->createAccount($_SESSION, $promo, $plannames, $planprices);   
}
?>    
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appreciate</title><meta http-equiv="Content-Language" content="en-us" />
<link rel="icon" type="image/png" href="logos/AppreciateLogo_1.png">
<link rel="stylesheet" href="css/font.css" />
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/livesearch.css" />
<script src="javascript/jquery.js"></script>
<script src="jQuery_val/dist/jquery.validate.min.js"></script>
<script src="javascript/jquery-ui.js"></script>
<script src="javascript/ajaxSubmit.1.8.js"></script>
<script src="javascript/ajaxcall.1.5.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="javascript/keys.js"></script>
<script type="text/javascript">Stripe.setPublishableKey(testpublickey);</script>
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
//print_r($_SESSION);
?>
