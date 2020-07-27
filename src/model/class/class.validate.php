<?php


class validate {
    
    public $error;
     
    public function getErrorCount() {
        $count = count($this->error);
        return $count;
        }
        
    public function getErrors() {
        return $this->error;
        }
        
    public function setError($error) {
        $this->error = $error;
    }    
    
    //check for empty values, set an error message if there are any.
    //if a value is not empty, send it to the sanitize public function to make it safe and secure
    public function required($key,$postvalue) {
    if (empty($postvalue)) {
            $this->error = ['error'=> ucfirst($key).' is a required field'];
            } else {
            $cleanvalue = self::sanitize($postvalue);
            return $cleanvalue;
    }
    }

    //same as above except it allows null values for optional fields
    public function optional($postvalue) {
            $value = self::sanitize($postvalue);
            if($value == ''){
            $value = NULL;
            }
            return $value;
    }

    public function sanitize($postvalue) {
            /*if (preg_match("/[\'^£$*()}{~<>,|=_+¬]/", $postvalue)){
                    $this->error[]  = "Special characters are not allowed";
            }else{*/
            return $postvalue;
            //  }
    }

    public function valArray($array) {
            $cleanarray = array_filter($array, $this->sanitize());
            return $cleanarray;
    }
    
    public function check_item ($checkitemstatement, $Errortext, $checkitem) {
            $itemcheckresult = pg_query($checkitemstatement);
            $itemcount = pg_num_rows($itemcheckresult);
            if($itemcount > 0){
            $this->error = ['error'=>$Errortext];
            return(NULL);		
            } else {
            return $checkitem;
            }
    }

    public function check_newuser ($checkitemstatement, $Errortext, $checkitem, $conn) {//same as above except this is checking the main AppreciateCo database
            $conn = $conn;
            $itemcheckresult = pg_query($conn,$checkitemstatement);
            $itemcount = pg_num_rows($itemcheckresult);
            if($itemcount > 0){
            $this->error = ['error'=>$Errortext];
            return(NULL);		
            } else {
            return $checkitem;
            }
    }

    public function alpha_only($postvalue) {
            if (!preg_match("/^[a-zA-Z ]*$/",$postvalue)) {
            $this->error = ['error'=>'Pleasee enter only letters'];
    }}

    public function currency_only($postvalue) {
    if(!empty($postvalue)){
            if(is_numeric($postvalue)){
            $postvalue = number_format($postvalue,2);
            return $postvalue;
            }else{
    $this->error = ['error'=>'Pleasee use currency format with no "$")'];	
    }}}

    //this doesn't work yet
    public function date_only($postvalue) {
            if(!preg_match("^[0-9]{4)-[0-9]{1,2)-[0-9]{1,2)$",$postvalue)){
            $this->error = ['error'=>'Pleasee use yyyy-mm-dd format'];
    }}

    public function account_already_exists($resetpage) {
            $this->error = ['error'=>'It looks like you alreay have an account. Would you like to<br><a href='.$resetpage.' style=text-decoration>reset your password</a>?'];
    }

    public function validate_email($postvalue) {
            if (!filter_var($postvalue, FILTER_VALIDATE_EMAIL)) {
                    $this->error = ['error'=>'Please enter a valid email address']; 	
                    }
    }
    
}
?>
