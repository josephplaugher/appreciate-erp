<?php
require_once 'class.user.php';

class tenantuser extends user {
   
    
    public function login($input,$dbh) {
      $user = $this->getUserData($input,$dbh);
      $this->checkPassword($input['password'],$user['password']);
      if(is_null($this->getErrors()) && isset($user['status'])) { 
               foreach($user as $key => $value) {
                    $_SESSION[$key] = $value;
                    }     
               $this->setStatus($user);
        }
    }
    
    public function createAccount($input,$dbh) {
  
        $user = $this->getUserData($input,$dbh);
        if(!empty($user['email'])){$this->setError('It looks like you already have an account.Try logging in.'); 
        }else{
            $this->insNewUser($input,$dbh);
            
        }
        if(!empty($this->error)) {
            $output = ['error'=>$this->error];
        }else{
            $output = ['success'=>'Thank you! Please check your email to continue.'];
        }        
    }
        
    private function getUserData($input,$dbh) {
        extract($input);
        try{
        $usercheck = $dsn->prepare('SELECT tenantid, status, email, password, lname, fname, maintcode,'
                . ' street,city,state,zip FROM tenant_login WHERE email = :email ');
        $usercheck->execute(['email'=>$email]);
        $user = $usercheck->fetch(PDO::FETCH_ASSOC);

        }catch (PDOException $pdoe) {
            $this->setError('user info query failed: '.$pdoe->getMessage());
        }
        return $user;
    }
    
    public function insNewUser($input,$dbh) {
        extract($input);
        $input['id'] = $this->generateId('tenant_id', $dbh);
        try{
        $usercheck = $dbh->prepare("INSERT INTO tenant_login (tenantid, status, email, password, lname, '
                . 'fname, maintcode, street,city,state,zip) VALUES (DEFAULT, 'unverified', :email, ;password, :lname, '
                . ':fname, :maintcode, :street, :city, :state, :zip)");
        $usercheck->execute(['tenantid'=>$tenantid, 'status'=>$status, 'email'=>$email, 'password'=>$password, 
        'lname'=>$lname,'fname'=>$fname, 'maintcode'=>$maintcode, 'street'=>$street,'city'=>$city,'state'=>$state,'zip'=>$zip]);
        }catch (PDOException $pdoe) {
            $this->setError('user info query failed: '.$pdoe->getMessage());
        }
    }
    
    public function setStatus($user) {
        extract($user);
        switch ($status) {

            case "unverified":
                $_SESSION['email'] = $email;
                $this->output = ['goto'=>'tui.php?user=newaccount'];
                break;
            case "verified":
                $_SESSION['login_user'] = $_SESSION['email'];
                $_SESSION['userstatus'] = "Welcome Back. Let's finish signing up.";
                $this->output = ['goto'=>'tui.php?user=companyinfo'];
                break;
            case "delinquent":
                $this->output = ['goto'=>'../updatepayment.php'];
                break;
            case "password":
                $_SESSION['changepassword'] = 'true';
                break;
            case "current":
                $_SESSION['login_user'] = $email;
                $_SESSION['company_connect'] = $company_name;
                $this->output = ['goto'=>'ui.php?class=home&method=main'];
            case "addeduser":    
                $_SESSION['login_user'] = $_SESSION['email'];
        }            
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
