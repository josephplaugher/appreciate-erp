<?php

require 'class.formelements.php';

class propNav extends formelements {
    
    public function getView() {
        $output = json_encode($this->view);
        print $output;
    }
    
    public function find() {   
        $output = $this->beginForm('Property Search','class=dataentry&method=add_entity');
        $output .= $this->input('Property ID','','id','');
        $output .= $this->input('Entity EIN','','ein','');
        $output .= $this->input('Entity Name','','entity','');
        $output .= $this->input('Short Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('clear','Clear',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
    
    public function profile() {   
        $output = $this->beginForm('Property Profile','class=dataentry&method=add_entity');
        $output .= $this->input('Employer ID Number','','ein','');
        $output .= $this->input('Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Function','','function','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
    
    public function capital() {   
        $output = $this->beginForm('Capital Structure','class=dataentry&method=add_entity');
        $output .= $this->input('Employer ID Number','','ein','');
        $output .= $this->input('Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Function','','function','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
    
    public function financials() {   
        $output = $this->beginForm('Financial Performance','class=dataentry&method=add_entity');
        $output .= $this->input('Employer ID Number','','ein','');
        $output .= $this->input('Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Function','','function','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
    
    public function units() {   
        $output = $this->beginForm('Units','class=dataentry&method=add_entity');
        $output .= $this->input('Employer ID Number','','ein','');
        $output .= $this->input('Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Function','','function','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
    
    public function tax() {   
        $output = $this->beginForm('Tax Status','class=dataentry&method=add_entity');
        $output .= $this->input('Employer ID Number','','ein','');
        $output .= $this->input('Name','','name','');
        $output .= $this->input('Type','','type','');
        $output .= $this->input('Taxation','','taxation','');
        $output .= $this->input('Function','','function','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = ['output'=>$output];
    }
}

?>