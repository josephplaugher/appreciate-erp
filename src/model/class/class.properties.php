<?php

require 'class.table.php';

class properties extends table{
    
    public $testdata;
    
    public function getTestData(){
        return $this->testdata;
    }
        
    public function find($input,$dbh) {
        $propheaders = ['ID','Short Name','Entity Name'];
        $query = 'SELECT id, shortname, entityname FROM props';
        $query .= $this->buildQuery($input);
        try{
        $prop = $dbh->prepare($query);
        $boundQuery = $this->bindVals($input,$prop);
        $boundQuery->execute();
        $result = $boundQuery->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('Find query failed: '.$pdoe->getMessage());
        }
        $output = $this->listTable($result,$propheaders);
        $this->output = $output;
    }
    
    private function buildQuery($input) {
        $query = '';
        $count = 0;
        foreach($input as $longkey => $val) {
            if($count > 0) {$and = ' AND ';}else{$and = '';}
            if(!empty($val[$count])) {
                $key = explode('-',$longkey);
                $query .= $and." WHERE ".$key[1]." = '".$val."'";
                $count ++;
            }
        }
        return $query;
    }
    
    private function bindVals($input,$prop) {
        try{
        foreach($input as $key => $val) {
            if(!is_null($formvalue)) {
                $val = split($key, '_');
                $prop->bindValue($val[1],$val);
            }
        }
        }catch(PDOException $pdoe){
            $this->setError('One or more binds failed: '.$pdoe->getMessage());
        }
        return $prop;
    }
    
    private function listTable($result,$headers){
        $output = $this->openTable();
        $output .= $this->basicHeader($headers);
        $output .= $this->basicData($result,NULL,'props'); //array and the key at which to terminate each table row
        $output .= $this->closeTable();
        return $output; 
    }
    
    public function profile($input,$dbh,$html,$form) {   
        extract($input);
        try{
        $this->testdata = $data = $dbh->query("SELECT id,ein, entityname,shortname,size,acq_date,pprice,dpmt,street,city,state,zip FROM props WHERE id = '$id' ")->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('get prop data query failed: '.$pdoe->getMessage());
        }
        $output = $form->beginForm('Property Profile | '.$data['id'],'class=updateProp&method=updateProp');
        $output .= $form->input('EIN',$data['ein'],'ein','');
        $output .= $form->input('Entity Name',$data['entityname'],'entityname','');
        $output .= $form->input('Short Name',$data['shortname'],'shortname','');
        $output .= $form->input('Size (units)',$data['size'],'size','');
        $output .= $form->input('Acquisition Date',$data['acq_date'],'acq_date','');
        $output .= $form->input('Purchase Price',$data['pprice'],'pprice','');
        $output .= $form->input('Down Payment',$data['dpmt'],'dpmt','');
        $output .= $form->input('Street',$data['street'],'street','');
        $output .= $form->input('City',$data['city'],'city','');
        $output .= $form->input('State',$data['state'],'state','');
        $output .= $form->input('Zip',$data['zip'],'zip','');
        
        $output .= $html->openDiv('buttondiv',NULL);
        $output .= $html->button('ajaxsubmit','Edit',NULL);
        $output .= $html->closeDiv();
        $output .= $form->closeForm();
        $this->output = $output;
    }
    
    public function capital($input,$dbh,$id = NULL) {   
        $headers = ['id', 'Entity Name', 'Contact', 'Percent'];
        $result = $this->getOwnerList($input,$dbh);
        $output = $this->listTable($result,$headers);
        $this->output = $output;
    }
      
    private function getOwnerList($input,$dbh) {
        extract($input);
        try{
        $units = $dbh->query("SELECT id, percent FROM capital WHERE prop_id = '$id' ")->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('get owners query failed: '.$pdoe->getMessage());
        }
        return $units;
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
    
    public function addProp($input,$dbh,$html,$form) {   
        $output = $form->beginForm('Add New Property','class=updateProp&method=updateProp');
        $output .= $form->input('EIN','','ein','');
        $output .= $form->input('Entity Name','','entityname','');
        $output .= $form->input('Short Name','','shortname','');
        $output .= $form->input('Size (units)','','size','');
        $output .= $form->input('Acquisition Date','','acq_date','');
        $output .= $form->input('Purchase Price','','pprice','');
        $output .= $form->input('Down Payment','','dpmt','');
        $output .= $form->input('Street','','street','');
        $output .= $form->input('City','','city','');
        $output .= $form->input('State','','state','');
        $output .= $form->input('Zip','','zip','');
        
        $output .= $html->openDiv('buttondiv',NULL);
        $output .= $html->button('ajaxsubmit','Save',NULL);
        $output .= $html->button('clear','Clear',NULL);
        $output .= $html->closeDiv();
        $output .= $form->closeForm();
        $this->output = $output;
    }
    
    public function units($input,$dbh,$id = NULL) {   
        $headers = ['id', 'unit', 'cur_ten', 'rent', 'deposit', 'rent_stat', 'paid_through'];
        $result = $this->getUnitList($input,$dbh);
        $output = $this->listTable($result,$headers);
        $this->output = $output;
    }
      
    private function getUnitList($input,$dbh) {
        extract($input);
        try{
        $units = $dbh->query("SELECT id, unit, cur_ten, rent, deposit, rent_stat, paid_through FROM units WHERE prop_id = '$id' ")->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('get units query failed: '.$pdoe->getMessage());
        }
        return $units;
    }
}

?>