<?php

class html {

    public function startHtml($pagename) {
        $output = '<!DOCTYPE html PUBLIC "//EN">';
        $output .= '<head>';
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $output .= '<title>Appreciate</title><meta http-equiv="Content-Language" content="en-us" />
        <link rel="icon" type="image/png" href="../logos/AppreciateLogo_1.png">';
        return $output;
    }
    
    public function css($file) {
        return '<link rel="stylesheet" href="css/'.$file.'.css" />';
    }
    
    public static function homeCss() {
    $output = '<link rel="stylesheet" media="screen and (max-width: 900px)" href="css/m.master.css" />';
    $output .= '<link rel="stylesheet" media="screen and (min-width: 901px)" href= "css/master.css" />';
    print $output;
    }
    
    public function mainCss() {
    $output = '<link rel="stylesheet" media="screen and (max-width: 900px)" href="../css/m.master.css" />';
    $output .= '<link rel="stylesheet" media="screen and (min-width: 901px)" href= "../css/master.css" />';
    return $output;
    }
    
    public function js($file) {
        return '<script src= "javascript/'. $file .'.js"></script>';
    }
    
    public static function jqValidate() {
        $output = '<script src="jQuery_val/dist/jquery.validate.min.js"></script>';
        //the additional methods breaks ajax for some reason
        //$output .= '<script src="../jQuery_val/dist/additional-methods.min.js"></script>';
        $output .= '<script src="javascript/ajaxSubmit.1.8.js"></script>';
        return $output;
    }
    
    public function stripePubKey($PUBLIC_KEY) {
        $output = '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>';
        $output .= '<script type="text/javascript">Stripe.setPublishableKey("'.$PUBLIC_KEY.'")</script>';
        return $output;
    }
   
    public function closeHtmlHead() {
        return '</head><body>';   
    }
    
    public function openHeaderDiv($header) {
        return '<div id = "header"><h2>'. $header. '</h2>';
    }
    
     public function header($header) {
        return '<h2>'. $header. '</h2>';
    }
    
    public function button($id, $value, $func) {
        if($id == 'submit'){$type = 'submit';}else{$type = 'button';}
        if(isset($func)) {$function = 'onclick = "'.$func.'()"';}else{$function = NULL;}
        return '<input class = "submit" type = "'.$type.'" id = "'. $id . '" value = "' . $value. '" '.$function.' >';
    }
    
    public function openDiv($id,$class) {
        return '<div id = "'.$id.'" class = "'.$class.'">';
    }
       
    public function closeDiv() {
        return '</div>';
    }
    
    public function para($input,$class) {
        return '<p1 class= "'.$class.'">'.$input.'</p1>';
    }
    
    public function displayImage($file,$alt) {
        return '<p><img src = "logos/'.$file.'" alt = "'.$alt.'"></p>';
    }        
    
    public static function lnbr() {
        print '<br>';
    }
    
    public function logoutForm() {
        $output = '<input type="button" class = "logout" name="logout" id="logout" value="Log Out"> ';
        $output .= '<input type="button" class = "logout" onclick="openNav()" id="menubutton" value="Menu">';
        return $output;
    }
        
    public function endHtml() {
        return '</body></html>';
    }
}

?>