 <?php

require_once 'class.formelements.php';

class tenantview extends formelements {
 
 public function createAccount() {   
        $this->formtitle = 'Create Account';
        $this->css = ['newuser'];
        $this->JS = ['newuser.1.2'];
        $output = $this->openDiv('page',NULL);
        $output .= $this->displayLogoImage('AppreciateLogo_4_H.png', 'Appreciate Corporation','index');
        $output .= $this->beginForm('Create Account','class=tenantuser&method=createAccount');
        $output .= $this->input('Last Name','','lname','');
        $output .= $this->input('First Name','','fname','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        $output .= $this->input('Email','','email','');
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Create Account',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
 }
 ?>