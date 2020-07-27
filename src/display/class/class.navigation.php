<?php

class navigation {
    
    public function mainLinks() {
        $output = '';
        $links = array('Administration'=>'settings','Accounting'=>'accounting','Banking'=>'banking',
            'Properties'=>'props','Customers'=>'customers','Suppliers'=>'suppliers','Maintenance'=>'maint',
            'Users'=>'users','Entities'=>'corps');
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "mainlinks" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";
        return $output;
    }

    public function mobileLinks() {
        $output = '';
        $links = array('Administration'=>'settings','Accounting'=>'accounting','Banking'=>'banking',
            'Properties'=>'props','Customers'=>'customers','Suppliers'=>'suppliers','Maintenance'=>'maint',
            'Users'=>'users','Entities'=>'corps');
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "sidenavlinks" onclick = "closeNav()" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";    
        return $output;
    }
    
        public static function propLinks() {
        $output = '';
        $links = array('Profitability'=>'props.profits','Accounting'=>'props.accounting','Taxes'=>'props.taxes',
            'Equity and Capital'=>'props.capital','Tenants'=>'props.tenants');
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "mainlinks" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";
        return $output;
    }

    public static function mobilePropLinks() {
        $output = '';
        $links = array('Profitability'=>'props.profits','Accounting'=>'props.accounting','Taxes'=>'props.taxes',
            'Equity and Capital'=>'props.capital','Tenants'=>'props.tenants');
            foreach($links as $key => $value) {
             $output .= '<input type = "button" id = "'.$value.'" class = "sidenavlinks" onclick = "closeNav()" style="cursor:pointer" value = "'. $key.'">'
                     . ''; 
            }
        $output .= "<br>";    
        return $output;
    }
    
}
?>