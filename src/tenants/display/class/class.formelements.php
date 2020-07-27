<?php

class formelements {

    public $formType;
    public $dbh;
    public $selectname;
    
    public function getMethodName() {
      return $this->formname;
    }
      
    public function getView() {
        print $this->view;
    }  
    
    public function getCss() {
        return $this->css;
    }
      
    public function getJS() {
        return $this->JS;
    }
    
    public function getFormTitle() {
        return $this->formtitle;
    }
    
    public  function beginForm($title,$action) {
        $url = '../model/sscont.php?'.$action;
        $id = str_replace(' ','_',$title);
        $output = '<div class="commonform" ><h3>'.$title.'</h3><form action= "'.$url.'" method="POST" id="'.$id.'" name="ajaxform">';
        $output .= '<div id = "usernotify" class = "usernotify"></div>';
        return $output;
    }
       
    public  function beginUserForm($title,$action) {
        switch($action) {
            case 'login':
                $url = '../model/users/login.php';
                break;
            case 'recovery':
                $url = '../model/users/recovery.php';
                break;
            case 'newuser':
            case 'companyinfo':
            case 'payment':
                $url = '../../model/users/newuser.php';
                break;
            case 'adduser':
                $url = '../model/sscont.php?class=adduser';
                break;
        }
        
        $output = '<div class="commonform" ><h3>'.$title.'</h3><form action= "'.$url.'" method="POST" id="ajaxform" name="ajaxform">';
        $output .= '<div id = "usernotify" class = "usernotify"></div>';
        return $output;
    }
    
    public function displayImage($file,$alt) {
        return '<p><img src = "logos/'.$file.'" alt = "'.$alt.'"></p>';
    } 
    
    public function displayLogoImage($file,$alt,$link) {
        return '<p><img src = "logos/'.$file.'" alt = "'.$alt.'" onclick="goto('.$link.')" style="cursor:pointer"></p>';
    }
              
    public function input($label,$value, $name, $options) {
        
        switch($label) {            
            case 'Password':
            case 'Current Password':   
            case 'Verify Password':    
                $inputtype = 'password';
                $fieldclass = 'textinput';
                break;
            
            case 'State': 
                $fieldclass = 'state';
                break;
            
            case 'Zip': 
                $fieldclass = 'zip';
                break;
            
            default: 
                $fieldclass = 'textinput';
        }
            
        if(empty($options)) {$options = NULL;}
        if(isset($inputtype)) {$type = $inputtype;}else{$type = 'input';}
        $ajax = $this->ajaxId($name);
	return '<div class = "label"><p1>'.$label.'</p1></div><div class = "field">'
             . '<input class = "'.$fieldclass.'" type="'.$type.'" name="'.$name.'" id="'.$name.'" value = "'.$value.'" '.$options.' autocomplete = "off">'.$ajax.'</div>';
    }
    
    function ajaxId($inputname) {
        $id = $inputname.'_opt';
        $ajax = '<p id = "'.$id.'" class = "options"></p>';
        return $ajax;
    }
    
    public  function plans($plannames,$planprices) {
        $output = '<div class = "label"><p1>Select A Plan</p1></div><div class = "field">';
        $output .= '<select name = "plan" id = "plandata" class = "select">';
	$output .= '<option value = "" ></option>';	
        $r = 0;
	foreach($plannames as $plannames) { 
            $item = $plannames.':'.$planprices[$r];
	$output .= "<option value='$item'>$item</option>";	
		$r = $r + 1;
	}
        $output .= '</select></div>';
        return $output;
    }
    
    
    public  function paymentInput($label) {
        
        switch($label) {
            case 'Card Number':
                $fieldclass = 'textinput';
                $length = 'maxlength = "16"';
                break;
            
            case 'CVC': 
                $fieldclass = 'CVC';
                $length = 'maxlength = "4"';
                break;
            
            case 'Expiration (MM/YYYY)': 
                $fieldclass = 'Exp';
                $length = 'maxlength = "7"';
                break;
         
            default: 
                $fieldclass = 'textinput';
        }
            
	return '<div class = "label"><p1>'.$label.'</p1></div><div class = "field"><input class = "'.$fieldclass.'" type="input" id="'.$label.'" '.$length.'></div>';
    }
    
    public function transtype($label,$value, $name, $options) {
        
        $output = '<div class = "label"><p1>'.$label.'</p1></div>';
        $output .= '<div class = "field">';
        $output .= '<select class = "select" name="'.$name.'">';
	$output .= '<option value="">Choose a transaction type</option>';
 	$output .= '<option value="ACH">ACH</option>';
	$output .= '<option value="CC">Credit Card</option>';
	$output .= '<option value="Check">Check</option>';
	$output .= '<option value="E-Check">E-Check</option>';
	$output .= '<option value="Debit">Debit</option>';
	$output .= '<option value="Transfer">Transfer</option>';
        $output .= '</select><br></div>';
        return $output;
    }
    
    public function accttype($label,$value, $name, $options) {
        
        $output = '<div class = "label"><p1>'.$label.'</p1></div>';
        $output .= '<div class = "field">';
        $output .= '<select class = "select" name="'.$name.'">';
	$output .= '<option value="">Choose an account type</option>';
 	$output .= '<option value="Bank">Bank</option>';
	$output .= '<option value="Current Asset">Current Asset</option>';
        $output .= '<option value="Fixed Asset">Fixed Asset</option>';
	$output .= '<option value="Credit Card">Credit Card</option>';
	$output .= '<option value="long-term Liability">Long-term Liability</option>';
	$output .= '<option value="Equity">Equity</option>';
	$output .= '<option value="Revenue">Revenue</option>';
        $output .= '<option value="Expense">Expense</option>';
        $output .= '<option value="Gain">Gain</option>';
        $output .= '<option value="Loss">Loss</option>';
        $output .= '<option value="Cost of Goods Sold">Cost of Goods Sold</option>';
        $output .= '<option value="Subsidiary">Subsidiary</option>';
        $output .= '</select><br></div>';
        return $output;
    }
    
    public  function hiddenField($name,$value,$id) {
        return '<input type="text" name= "'.$name.'"  value = "'.$value.'" id = "'.$id.'" hidden >';
    }
   
	
    public function closeForm() {
        return '</form>';
    }
    
    public  function stmtDates($stmt,$startdate,$enddate) {
        
        $output = "Date<br>";
        if($stmt !== 'balancesheet'){
            $output .= 'From<input type = "text" name = "startdate" class= "date" value = "'.$startdate.'"/>';
        }
	$output .= 'To<input type = "text" name = "enddate" class= "date" value = "'.$enddate.'"><br><br>';
        return $output;
    }
    
    public function terms() {
        $output = '<div class = "label"><p1>Terms</p1></div>';
        $output .= '<div class = "field"><select name = "terms" class = "select">';
        $output .= '<option value="Due Upon Receipt">Due Upon Receipt</option>';
        $output .= '<option value="Net 30">Net 30</option>';
        $output .= '<option value="1/10 n 30">1/10 n 30</option>';
        $output .= '<option value="2/10 n 30">2/10 n 30</option>';
        $output .= '<option value="1/10 n 15">1/10 n 15</option>';
        $output .= '<option value="2/10 n 15">2/10 n 15</option>';
        $output .= '</select></div>';
        return $output;
    }
    public  function invoiceLinesHeader() {
        return '<div  class = "item-header">Item</div><div class = "price-header">Price</div><div class = "quant-header">Quantity</div>';  
    }
    
    public  function invoiceLines() {
        return '<div id ="toclone"><input type = "text" class = "item" name = "item[]" size = "70"><input type = "text" class = "price" name = "price[]"><input type = "text" class = "quant" name = "quant[]" size = "10"></div>';
    }
    
    public  function journLinesHeader() {
        return '<div  class = "item-header">Account</div><div  class = "item-header">Account No</div><div class = "price-header">Deb/Cred</div><div class = "quant-header">Amount</div>';       
    }
    
    public  function journLines() {
        //this method creates a standard dropdown list. But i want to use AJAX livesearch
        /*$output = '<div><select name="acct[]" class = "select"><option value = "" disabled selected></option>';
		foreach($list as $list) {
            $output .= '<option value = "'.$list['acctname'].' : '.$list['acctno'].'">'.$list['acctname'].' : '.$list['acctno'].'</option>';
            }
        
	$output .= '</select>';
        */
        $ajax = $this->ajaxId('acctname');
        $output = '<div id ="toclone"><div><input name="acctname[]" class = "account">'.$ajax.'</div>';
        $ajax = $this->ajaxId('acctno');
        $output .= '<div><input name="acctno[]" class = "accountno">'.$ajax.'</div>';
        $output .= '<div><select name="dorc[]" class  = "price">
	<option value = "" disabled selected></option>
	<option value = "debit">Debit</option>
	<option value = "credit">Credit</option>
	</select>
        <input type = "text" size = "10" class = "price" name = "amt[]">
	</div></div>';
        return $output;
    }
    
    public  function reportFilter($entities) {
        $output = '<div class = "label"><p1>Entity</p1></div>';
        $output .= '<div class = "field" id = entityselect><select name = "entity" id = "entity" class = "select">';
	$output .= '<option value = "" ></option>';	
            foreach($entities as $list) { 
            $output .= "<option value=".$list['ein'].">".$list['name'].": ".$list['ein']."</option>";
            }
        $output .= '</select></div>';
        $output .= '<div class = "label"><p1>Property</p1></div>';
        $output .= '<div class = "field" id = propertyselect><select name = "property" id = "property" class = "select"></select></div>';
        $output .= '<div class = "label"><p1>Unit</p1></div>';
        $output .= '<div class = "field" id = unitselect><select name = "unit" id = "unit" class = "select"></select></div>';
        return $output;
    }
    
    public  function dropDown($label,$id,$name,$data) {
    $output = '<div class = "label"><p1>'.$label.'</p1></div>';
    $output .= '<div class = "field"><select id = "'.$id.'" name= "'.$name.'" class  = "select">';
    foreach($data as $key => $value) {
	$output .= '<option value = "'.$key.'">'.$value.'</option>';
	}
	$output .='</select></div>';    
    return $output;
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
    
    public function button($id, $value, $func = NULL) {
        return '<input class = "submit" name = "'.$value.'" type = "button" id = "'. $id . '" value = "' . $value. '" '.$func.' >';
    }
    
    
    
}

