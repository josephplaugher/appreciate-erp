<?php

require_once 'class.formelements.php';

class tenanthome extends formelements {
    
    public $links = ['Payment History'=>'pmtHistory', 'Pay Rent'=>'payRent','New Maintenace Requests'=>'newReq','Maintenace Requests'=>'maintHistory'];
    
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
    
    public function main($html) {
        $this->css = ['master','submaster','font'];
        $this->JS = ['navigation','sublinks'];
        $this->formtitle = 'Home';
        $output = $html->openDiv("mainpage-container", NULL);
        $output .= $html->openDiv('appreciate', NULL);
        $output .= $html->displayImage('AppreciateLogo_4_H.png', 'Appreciate Corporation');
        $output .= $html->closeDiv();
        
        $output .= $this->openDiv('mainco-name', NULL);
        $output .= $html->para('Hello '.$_SESSION['fname']. " | Tenant Center<br>",NULL);
        $output .= $this->menuLogOut();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('maintop-nav',NULL);
        $output .= $this->mainLinks();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('mySidenav','sidenav');
        $output .= $this->mobileLinks();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('maincontent',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
           $this->view = $output;
    }
    
    public function mainLinks() {
        $output = '';
        $links = $this->links;
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "mainlinks" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";
        return $output;
    }

    public function mobileLinks() {
        $output = '<input type="button" class = "submit" onclick = "closeNav()" name="X" value="X"> ';
        $links = $this->links;
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "sidenavlinks" onclick = "closeNav()" style="cursor:pointer" value = "'. $key.'">'; 
            }
        $output .= "<br>";    
        return $output;
    }
    
    public function menuLogOut() {
        $output = '<input type="button" class = "logout" name="logout" id="logout" value="Log Out"> ';
        $output .= '<input type="button" class = "logout" onclick="openNav()" id="menubutton" value="Menu">';
        return $output;
    }
}

?>

