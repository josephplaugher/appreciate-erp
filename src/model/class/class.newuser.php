<?php

require_once 'class.user.php';

class newuser extends user {

    public function createAccount($input) {
        $dsn = $this->newUserDSN();   
        $user = $this->getUserData($input,$dsn);
        if(!empty($user['email'])){$this->setError('It looks like you already have an account. Try logging in.'); 
        }else{
            $this->insNewUser($input,$dsn);
            $this->newUserSession($input);
            require 'swiftmailer-5.x/lib/swift_required.php';
            $swiftTransport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "SSL");
            // Create the Mailer using the Transport
            $swiftMailer = Swift_Mailer::newInstance($swiftTransport);
            $swiftMessage = Swift_Message::newInstance('Welcome to Appreciate!');
            $this->newUserEmail($input,$swiftTransport,$swiftMailer,$swiftMessage,$resend);
            $this->output = ['success'=>'Thank you! Please check your email to continue.'];
        }        
    }
    
    private function insNewUser($input,$dsn) {
        unset($input['passwordmatch']);
        extract($input);
        $pw = $this->hashPassword($input['password']);
        try{
        $newuser = $dsn->prepare("INSERT INTO login (email, password, fname, lname, admin, status)
                               VALUES (:email, :password, :fname, :lname, 1, 'unverified')"); 
        $userdetails = $newuser->execute(['email'=>$loginemail, 'password'=>$pw,'fname'=>$fname,'lname'=>$lname]);
        }catch (PDOException $pdoe) {
              $this->setError('insert user data failed: '.$pdoe->getMessage());
        }
    }
    
    private function newUserSession($input) {
        extract($input);
        $_SESSION['loginemail'] = $loginemail;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
    }
    
    public function newUserEmail($input,$swiftTransport,$swiftMailer,$swiftMessage,$resend) {    
        extract($input);
        date_default_timezone_set('America/Los_Angeles');//required by swiftmailer
        // Create the Transport
        try{
        $swiftTransport->setUsername('joseph@appreciateco.com')->setPassword('apache1985');
        // Create the Mailer using your created Transport
        // Create a message
        $body = "<p>Hello $fname,<br><br>Welcome to Appreciate accounting and business management software! It won't take long to finish creating your account."
                . "<a href='https://appreciateco.com/model/usercont.php?class=newuser&method=newUserVerify&email=$loginemail'> Click here</a> to verify your email address.<br><br>"
                . "Account Management<br>Appreciate Corporation</p>";

        $swiftMessage->setFrom(array('accounts@appreciateco.com' => 'Appreciate Corporation'))->setBody($body, "text/html");
        $swiftMessage->setTo([$loginemail => $fname]);
        $numSent = $swiftMailer->send($swiftMessage);
            if(!($numSent > 0)) {
                Throw new Exception('The new user database entry failed');
            }else{ $this->setEmailNotif($resend);}
        }catch(PDOException $pdoe){
            $error = $pdoe->getMessage();
        }catch(Exception $e){
            $error = $e->getMessage();
        }
        if($error){
            $this->setError($error);
        }
    }
    
    public function setEmailNotif($resend) {
        if($resend == 'true'){
            $this->output = ['success'=>'Thank you! The verification email has been resent.'];
        }else{
            $this->output = ['success'=>'Thank you! Check your email to continue.'];        
        }
    }
    
    public function newUserVerify($newUserEmail) {
        $dsn = $this->newUserDSN();  
        try{
        $result = $dsn->prepare("SELECT email, fname, lname, status FROM login WHERE email = :email ");
        $result->execute(['email'=>$newUserEmail]);
        $user = $result->fetch(PDO::FETCH_ASSOC);

            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['loginemail'] = $user['email'];
            $status = $user['status'];
            
            switch ($status) {
            case NULL:
                print "something went wrong, user status: ".$status;
                print_r($result);
                break;
            case 'verified':
            case 'current':    
                header('location: ../../display/newuser.php?class=userview&method=login');
                break;
            case 'unverified':
                $update = $dsn->prepare("UPDATE login SET status = 'verified' WHERE email = :email");
                $update->execute([$newUserEmail]);
                header('location: ../../display/newuser.php?class=userview&method=companyinfo');
                break;
            case 'addeduser':
                $addupdate = $dsn->prepare("UPDATE login SET status = 'current' WHERE email = :email");
                $addupdate->execute([$newUserEmail]);
                header('location: ../../display/newuser.php?class=userview&method=changepasswword');
                break;
            }
            }catch (PDOException $i){
                // report error message
            print "There was a problem verifying your account: ". $i->getMessage();
            }
    }
    
    public function newUserInfo($input){
        $dsn = $this->newUserDSN(); 
        extract($input);
        $plan = explode(':', $plan);
        $_SESSION['company_name'] = $company_name;
        $_SESSION['industry'] = $industry;
        $_SESSION['plan'] = $plan[0];
        $_SESSION['promo'] = $promo;
        $_SESSION['price'] = $price;
        $_SESSION['pmtstage'] = 'true';
        $loginemail = $_SESSION['loginemail'];
        try{
        $update = $dsn->prepare("UPDATE login SET(company_name, industry, plan) = (:company_name, :industry, :plan) WHERE email = :email");
        $update->execute(['company_name'=>$company_name, 'industry'=>$industry, 'plan'=>$plan,'email'=>$loginemail]);
        }catch (PDOException $i){
        $this->setError('error entering userinfo: '. $i->getMessage());
        }
        $this->output = ['goto'=>'../../display/newuser.php?class=userview&method=payment'];
    }
    
}
?>