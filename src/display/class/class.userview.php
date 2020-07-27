<?php

require_once 'class.formelements.php';

class userview extends formelements {
            
    public function notUser() {  
        $email = $session['email'];
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginUserForm('You are signed in under '.$email,'class=user&method=logOut');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Not me, sign out',NULL);
        $output .= $this->button('home',"Let's Get to Work",NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
    public function updatepassword($session) {  
        $fname = $session['fname'];
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm("Hello $fname. Change Your Password.",$this->formname);
        $output .= $this->input('Current Password','','currentpassword','autocomplete="off"');
        $output .= $this->input('New Password','','password','autocomplete="off"');
        $output .= $this->input('Verify New Password','','passwordmatch','autocomplete="off"');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
    
    public function recovery($session) {  
        $fname = $session['fname'];
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginUserForm('Reset Your Password',$this->formname);
        $output .= $this->input('Email','','loginemail','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
      
    public function login() {   
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Log In','class=user&method=login');
        $output .= $this->input('Email','','loginemail','');
        $output .= $this->input('Password','','password','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Log In',NULL);
        $output .= $this->button('createaccount','Create Account',NULL);
        $output .= $this->button('resetpassword','Reset Password',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
    public function createaccount($sess,$promo,$plannames,$planprices) {   
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Create New Account','class=newuser&method=createAccount','userform');
        $output .= $this->input('Last Name','','lname','');
        $output .= $this->input('First Name','','fname','');
        $output .= $this->input('Email','','loginemail','');
        $output .= $this->input('Password','','password','');
        $output .= $this->input('Verify Password','','passwordmatch','autocomplete="off"');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Create Account',NULL);
        $output .= $this->button('login','Log In',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
     public function resendEmail() {   
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.4'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Your account still needs to be verified. Do you need another verification email?','class=newuser&method=newUserEmail&resend=true','userform');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Send Another Email',NULL);
        $output .= $this->button('login','Log In',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
    public function companyinfo($sess,$promo,$plannames,$planprices) { 
        $this->css = ['newuser','payment'];
        $this->JS = ['newuser.1.4','ajaxcall.1.5'];
        $this->formtitle = 'Company Information';
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Company Information','class=newuser&method=newUserInfo','userform');
        $output .= $this->para($sess['userstatus'], 'welcomeback');
        $output .= $this->input('Company Name','','company_name',NULL);
        $ind = ['rei'=>'Real Estate Investment','other'=>'Other'];
        $output .= $this->dropDown('Industry', 'industry', 'indusry', $ind);
        $output .= $this->plans($plannames,$planprices); 
        $output .= $this->input('Promotional Code',$promo,'promo',NULL); 
        $output .= $this->input('Discount','','discount','readonly'); 
        $output .= $this->input('Price','','price','readonly'); 
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Continue',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
      public function payment() { 
        $this->css = ['newuser','payments'];
        $this->JS = ['newuser.1.4','ajaxcall.1.2','subscribe','userform'];
        $this->formtitle = 'Company Information';
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Payment','class=subscribe&method=processToken','userform');
        $output .= $this->openDiv('planselections',NULL);
        if(!is_null($_SESSION['promo'])){ $_promo = "<br>Promo: ".$_SESSION['promo'];}
        $output .= $this->para("Plan: ".$_SESSION['plan']." ".$_promo."<br>Price: ".$_SESSION['price']."<br>",'');
        $output .= $this->closeDiv();
        $pmtarray = ['Card Number','CVC','Expiration (YYYY/MM)'];
        foreach($pmtarray as $c) {
            $output .= $this->paymentInput($c);
        }  
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('subscribe','Finish and Pay',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
           
}
?>