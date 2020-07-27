<?php
require 'class.base.php';

class user extends base {
  
    public $logindsn = "pgsql:host=localhost;port=5432;dbname='liquidphase';user=postgres;password=skippy1985";
    
    public function newUserDSN() {
        try{  
        $dsn = new PDO($this->logindsn);  
        }catch (PDOException $pdoe) {
            $this->setError('first connection failed: '.$pdoe->getMessage());
        }
        return $dsn;
    }
    
    public function login($input) {
        $dsn = $this->newUserDSN();  
        $user = $this->getUserData($input,$dsn);
        $this->setStatus($user);
        if($user['status'] == 'current'){
            
            $this->checkPassword($input['password'],$user['password']);
            if(is_null($this->getErrors()) && isset($user['status'])) { 
                    foreach($user as $key => $value) {
                        $_SESSION[$key] = $value;
                    }
                    $this->setAccess($user);     
        }
      }
    }
        
    protected function getUserData($input,$dsn) {
        extract($input);
        try{
        $usercheck = $dsn->prepare('SELECT company_id, customerid, empid, status, email, company_name, lname, fname, password, admin, '
                . 'industry, maintcode FROM login WHERE email = :email ');
        $usercheck->execute(['email'=>$loginemail]);
        $user = $usercheck->fetch(PDO::FETCH_ASSOC);

        }catch (PDOException $pdoe) {
            $this->setError('user info query failed: '.$pdoe->getMessage());
        }
        return $user;
    }
    
    private function setAccess($user) { 
        extract($user);
        try{
        $userconn = $this->getUserDSN($company_id);
        $accesslist = $userconn->prepare("SELECT lname, fname, withdrawals, deposits, reconcile_bank, undo_bank_rec, "
            . "general_ledger, journal_entries, edit_coa, ar, ap, fin_stmts, administrator, view_prop, edit_prop "
            . "FROM access WHERE email = :loginemail ORDER BY lname, fname");
        $accesslist->execute(['loginemail'=>$loginemail]);
        $access = $accesslist->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $pdoe) {
            $this->setError('access query failed '. $pdoe->getMessage());
        }
        if(is_null($accesslist)){ $this->setError('accesslist not set');}
        foreach($accesslist as $key => $value) { //sets all the access variables
            $_SESSION[$key] = $value;
        }
    }
    
    public function setStatus($user) {
        extract($user);
        switch ($status) {
            case NULL:
            case "":
                $this->setError('Username or Password is Invalid');
                break;
            case "unverified":
                $_SESSION['loginemail'] = $email;
                $this->output = ['goto'=>'../display/newuser.php?class=newuser&method=resendEmail'];
                break;
            case "verified":
                $_SESSION['loginemail'] = $_SESSION['email'];
                $_SESSION['userstatus'] = "Welcome Back. Let's finish setting up your account.";
                $this->output = ['goto'=>'../../display/newuser.php?class=userview&method=companyinfo'];
                break;
            case "delinquent":
                $this->output = ['goto'=>'../updatepayment.php'];
                break;
            case "password":
                $_SESSION['changepassword'] = 'true';
                break;
            case "current":
                $_SESSION['company_connect'] = $company_id;
                $this->output = ['goto'=>'../../display/ui.php?class=home&method=main'];
            case "addeduser":    
                $_SESSION['loginuser'] = $_SESSION['email'];
        }            
    }
    
    public function getStatus() {
        return $this->status;
    }  
    
    public function getUserDSN($company) {
        try{
        $userdsn = 'pgsql:host=localhost;port=5432;dbname='.$company.';user=postgres;password=skippy1985';
        $userconn = new PDO($userdsn);
        $userconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $pdoe) {
            $this->setError('set user dsn failed '. $pdoe->getMessage());
        }
        return $userconn;
    }
    
    public function passwordMatch($pw1,$pw2) {
        if(!($pw1 == $pw2)) {
        $this->error = ['error'=>'Your passwords do not match'];
        }
    }

    public function hashPassword($input) {
        $clnpassword = $input;
        $password = password_hash($clnpassword, CRYPT_BLOWFISH,["cost" => 14]);
        return $password;
    }
   
    public function checkPassword($userinput,$dbvalue) {
        if(!(password_verify($userinput, $dbvalue))){
            $this->setError('Username or Password is Invalid');
        }
    }

    public function checkVerify($userinput,$dbvalue) {	
        $password_entered = $userinput;
        $realpw = $dbvalue;
        $checkpassword = password_verify($password_entered, $realpw);
        if($checkpassword == True) {
            return $checkpassword;
            }else {$this->setError = ['error'=>'Email or verification code is invalid'];
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
    
    public function logOut() {
        session_start();	
	session_unset();
	session_destroy();
	setcookie(session_name(),'',0,'/');
   	session_regenerate_id(true);
	$this->output = ['goto'=>'ui.php?class=userview&method=login'];
    }
}
