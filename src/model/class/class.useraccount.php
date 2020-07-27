<?php
require_once 'class.validate.php';

class useraccount extends validate {
    
    public $status = array();
    public $logindsn;
    public $password;
    
    public function setStatus($status) {
        
        switch ($status) {

            case "unverified":
            $this->setError('You still need to verify your email address');
            break;

            case "verified":
            $output = ['status'=>'verified'];
            break;

            case "delinquent":
            $output = ['status'=>'delinquent'];
            break;

            case "current":
            $output = ['error'=>'This email address is already in use. Do you already have an account with us?'
            . ' If so, please sign in. If not, please choose a uquique email address.'];
            break;
        
            case "newuser":
            $output = ['status'=>'newuser'];
            break;
            
            case "emailsent":
            $output = ['status'=>'Thank you! Check your email to continue sign up!'];
            
        }
        $this->status = $output;          
    }
    
    public function getStatus() {
        return $this->status;
    }
            
    public function getDSN() {
        return "pgsql:host=localhost;port=5432;dbname='liquidphase';user=postgres;password=skippy1985";
        }   
    
    public function getUserDSN($company) {
        return "pgsql:host=localhost;port=5432;dbname='$company' ;user=postgres;password=skippy1985";
        }
    
    public function passwordMatch($pw1,$pw2) {
        if(!($pw1 == $pw2)) {
        $this->error = ['error'=>'Your passwords do not match'];
        }
        }

    public function hashPassword($input) {
        $clnpassword = $input;
        $password = password_hash($clnpassword, CRYPT_BLOWFISH,["cost" => 14]);
        $this->password = $password;
      }
    
    public function getHashedPassword() {
      return $this->password;
    }  

    public function checkPassword($userinput,$dbvalue) {
        if(!(password_verify($userinput, $dbvalue))){
            $this->error = ['error'=>'Username or Password is Invalid'];
        }
    }

    public function checkVerify($userinput,$dbvalue) {	
    $password_entered = $userinput;
    $realpw = $dbvalue;
    $checkpassword = password_verify($password_entered, $realpw);
    if($checkpassword == True) {
            return $checkpassword;
            }else {$this->error = ['error'=>'Email or verification code is invalid'];
            }
    }

    public function genpassword(){
    //generate random temporary password
    $r1 = str_shuffle(substr('abcdefghijklmnopqrstuvwxyz',0,3));
    $r2 = str_shuffle(substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ',0,3));
    $r3 = str_shuffle(substr('0123456789',0,2));
    $r4 = str_shuffle(substr('@#$%&!?*',0,1));
    
    $randpw = $r1;
    $randpw .= $r2;
    $randpw .= $r3;
    $randpw .= $r4;
    
    $randompw = str_shuffle($randpw);
    return $randompw;
}
}
