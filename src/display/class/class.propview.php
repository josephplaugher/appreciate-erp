<?php

require_once 'class.formelements.php';

class propview extends formelements {
    
    public $menu = ['Property Search'=>'find','Property Profile'=>'profile','Equity and Capital'=>'capital',
       'Financials'=>'financials','Units'=>'units','Tax Status'=>'tax','Add Property'=>'addProp'];
    
    public function main() {
        $this->css = ['master','submaster','table','scroll','props'];
        $this->JS = ['propNav','livesearch'];
        $output = $this->openDiv("mainpage-container", NULL);   
        
        $output .= $this->openDiv('appreciate', NULL);
        $output .= $this->propHeader();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('maintop-nav',NULL);
        $output .= $this->mainLinks();
        $output .= $this->closeDiv();
        $output .= '<div id = "usernotify" class = "usernotify"></div>';
        $output .= $this->menu();
        
        $output .= $this->openDiv('mySidenav','sidenav');
        $output .= $this->mobileLinks();
        $output .= $this->closeDiv();
        $output .= '<div id = "usernotify" class = "usernotify"></div>';
              
        $output .= $this->openDiv('maincontent',NULL);
        
        $output .= $this->openDiv('search',NULL);
        $output .= $this->propSearch();
        $output .= $this->openDiv('scrollcontainer',NULL);
        $output .= $this->openDiv('scroll',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();//search
        
        $output .= $this->openDiv('data',NULL);
        $output .= $this->closeDiv();//data
        
        $output .= $this->closeDiv();//maintcontent
        
        $output .= $this->closeDiv(); //mainpage-container
        $this->view = $output;
    }
    
    private function propHeader() {
        return '<div class = "propheader"><p>Property | </p></div><div class = "propheader" id="propheader"></div>';
    }
        
    public function mainLinks() {
        $output = '';
        $links = $this->menu;
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "mainlinks" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";
        return $output;
    }

    public function mobileLinks() {
        $output = '<input type="button" class = "submit" onclick = "closeNav()" name="X" value="X"> ';
        $links = $this->menu;
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "sidenavlinks" onclick = "closeNav()" style="cursor:pointer" value = "'. $key.'">'; 
            }
        $output .= "<br>";    
        return $output;
    }
    
    public function menu() {
        $output = '<input type="button" class = "logout" onclick="openNav()" id="menubutton" value="Menu">';
        return $output;
    }
    
    public function propSearch() {   
        $output = $this->beginForm('Property Search','class=properties&method=find');
        $output .= $this->input('Property ID','','prop-id','');
        $output .= $this->input('Entity EIN','','prop-ein','');
        $output .= $this->input('Entity Name','','prop-entityname','');
        $output .= $this->input('Short Name','','prop-shortname','');
        $output .= $this->input('Street','','prop-street','');
        $output .= $this->input('City','','prop-city','');
        $output .= $this->input('State','','prop-state','');
        $output .= $this->input('Zip','','prop-zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('clear','Clear',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        return $output;
    }
}

?>