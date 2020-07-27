<?php

require_once 'class.base.php';

class newacct extends base{
    
    public function add_account($input,$dbh) {//this method needs edits
        switch($input['accttype']) {
            case 'Fixed Asset':
                $this->fixedAsset($input, $dbh);
                break;
            case 'Expense':
                $this->expense($input, $dbh);
                break;
            case 'Subsidiary':
                $this->subsidiary($input, $dbh);
                break;
            default:
            
            extract($input);
            try{
            $this->checkDuplicate($acctno,$dbh);
                    if(is_null($this->getErrors())){
                    $newacct = $dbh->prepare("INSERT INTO sys_coa (acctno, acctname, description, type) VALUES
                                                               (:acctno, :acctname, :description, :type)");
                    $newacct->execute(['acctno'=>$acctno, 'acctname'=>$acctname, 'description'=>$description, 'type'=>$accttype]);
                    }
            }catch(PDOException $pdoe){
                $this->setError('Query failed: '.$pdoe->getMessage());
            } 
            if(is_null($this->getErrors())){
                $this->output = ['success'=>'New account created successfully'];
            }
        }
    }

    private function fixedAsset($input,$dbh) {
        extract($input);
        $this->checkDuplicate($acctno,$dbh);
        $this->checkDuplicate($cor_acctno,$dbh);
        if(is_null($this->getErrors())){
            try{
                $newacct = $dbh->prepare("INSERT INTO sys_coa (acctno, acctname, description, type, cor_acctno, cor_acctname) VALUES
                                                           (:acctno, :acctname, :description, :type, :cor_acctno, :cor_acctname)");
                //make the asset account
                $newacct->execute(['acctno'=>$acctno, 'acctname'=>$acctname, 'description'=>$description, 'type'=>'Fixed Asset', 
                                                     'cor_acctno'=>$cor_acctno,'cor_acctname'=>$cor_acctname]);
                //make the contra account
                $newacct->execute(['acctno'=>$cor_acctno, 'acctname'=>$cor_acctname, 'description'=>NULL, 'type'=>'Contra Asset', 
                                                     'cor_acctno'=>$acctno,'cor_acctname'=>$acctname]);
            }catch(PDOException $pdoe){
                $this->setError('Query failed: '.$pdoe->getMessage());
            } 
            if(is_null($this->getErrors())){
                $this->output = ['success'=>'New account created successfully'];
            }
        }
    }
                
    private function expense($input,$dbh) {
        extract($input);
        $this->checkDuplicate($acctno,$dbh);
        if(is_null($this->getErrors())){
            try{
                $newacct = $dbh->prepare("INSERT INTO sys_coa (acctno, acctname, description, type, subtype) VALUES
                                                               (:acctno, :acctname, :description, :type, :subtype)");
                $newacct->execute(['acctno'=>$acctno, 'acctname'=>$acctname, 'description'=>$description, 'type'=>$accttype, 'subtype'=>$subtype]);
            }catch(PDOException $pdoe){
                $this->setError('Query failed: '.$pdoe->getMessage());
            } 
            if(is_null($this->getErrors())){
                $this->output = ['success'=>'New account created successfully'];
            }
        }
    }
        
    private function subsidiary($input,$dbh) {
        extract($input);
        $this->checkDuplicate($acctno,$dbh);
        $this->checkValidAccount($cor_acctno,$cor_acctname,$dbh);
        if(is_null($this->getErrors())){
            try{     
                $newacct = $dbh->prepare("INSERT INTO sys_coa (acctno, acctname, description, type, cor_acctno, cor_acctname) VALUES
                                                           (:acctno, :acctname, :description, :type, :cor_acctno, :cor_acctname)");
                //make the asset account
                $newacct->execute(['acctno'=>$acctno, 'acctname'=>$acctname, 'description'=>$description, 'type'=>$accttype, 
                                                     'cor_acctno'=>$cor_acctno,'cor_acctname'=>$cor_acctname]);
            }catch(PDOException $pdoe){
                $this->setError('Subsidiary query failed: '.$pdoe->getMessage());
            } 
            if(is_null($this->getErrors())){
                $this->output = ['success'=>'New account created successfully'];
            }
        }
    }

    private function checkDuplicate($acctno, $dbh) {//will move this function into the new user class when I get around to that
        //check the email for duplicates
        try{
        $check = $dbh->query("SELECT acctno FROM sys_coa WHERE acctno = '$acctno' ")->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('Check duplicate failed: '.$pdoe->getMessage());
        }
        if(!(is_null($check['acctno']))) { $this->setError('That account number is already in use.');}
    }

    private function checkValidAccount($acctno, $acctname, $dbh) {//will move this function into the new user class when I get around to that
        //check the email for duplicates
        try{
        $acctno = $dbh->query("SELECT acctno FROM sys_coa WHERE acctno = '$acctno' AND acctname = '$acctname' ")->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('check valid account failed: '.$pdoe->getMessage());
        }
        if(is_null($acctno)) { $this->setError('That parent account does not exist.');}
    }
    
    public function update_acct($input, $dbh) {
        extract($input);
        unset($accttype);
        try{
        $update = $dbh->prepare("UPDATE sys_coa SET (acctname, description) =
                                (:acctname, :description) WHERE acctno = :acctno");
        $update->execute(['acctname'=>$acctname, 'description'=>$description, 'acctno'=>$acctno]);
                    
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if(is_null($this->getErrors())){
            $this->output = ['success'=>'Account updated successfully'];
        }
        
    }
    
    

}