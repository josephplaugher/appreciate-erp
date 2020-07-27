<?php

require_once 'class.formelements.php';

class prop extends formelements {
    
    public $menu = ['Find Property'=>'find','Property Setup'=>'profile','Equity and Capital'=>'capital','Financials'=>'financials','Units'=>'units','Tax Status'=>'tax'];
    
    public function main() {
        $this->css = ['master','submaster','font','props'];
        $this->JS = ['propNav'];
        $output = $this->openDiv("mainpage-container", NULL);   
        
        $output .= $this->openDiv('appreciate', NULL);
        $output .= $this->para('Property',NULL);
        $output .= $this->propSearch();
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
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
    
    private function propSearch() {
        $ajax = $this->ajaxId('propSearch');
        return '<div class = "propSearch">Property Search</div><div class = "field">'.$this->propSearchOption().'<input class = "propinput" type="text" name="propSearch" id="propSearch" value = "" autocomplete = "off">'.$ajax.'</div>';
    }
    
    private function propSearchOption() {
        $output = 'search by<select id = "address" name= "searchby" class  = "select">';
        $output .= 'option';
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
}

?>