<?php

require_once 'class.formelements.php';

class home extends formelements {
    
    public $links;
        
    public function __construct() {
        if($_SESSION['industry'] == 'rei'){
        $this->links = ['Administration'=>'settings','Accounting'=>'accounting','Banking'=>'banking',
            'Properties'=>'props','Customers'=>'customers','Suppliers'=>'suppliers','Maintenance'=>'maint',
            'Users'=>'users','Entities'=>'corps'];
        }else{
        $this->links = ['Administration'=>'settings','Accounting'=>'accounting','Banking'=>'banking',
            'Customers'=>'customers','Suppliers'=>'suppliers','Users'=>'users','Entities'=>'corps'];
        }
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
        $output .= $html->para($_SESSION['lname']. " | ". str_replace('_',' ',$_SESSION['company_name'])."<br>",NULL);
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
        foreach($this->links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "mainlinks" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";
        return $output;
    }

    public function mobileLinks() {
        $output = '<input type="button" class = "submit" onclick = "closeNav()" name="X" value="X"> ';
        foreach($this->links as $key => $value) {
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