<?php

require_once 'class.base.php';

class finstatements extends base {
    public $csv = array();
    public $income;
    public $cogs;
    public $exp;
    public $curassets;
    public $fixedassets;
    public $curliab;
    public $ltliab;
    public $equity;
 
    public function setHeader($input,$type) {
        extract($input);
        $csv[] = ['',$type];
        $csv[] = ['','From '.$startdate.' to '.$enddate.''];
        if($property && $entity && $unit){
        $csv[] = ['',$entity];
        $csv[] = ['',$property];
        $csv[] = ['',$unit];
        }
        return $csv;
    }
      
    public function getAccountNames($dbh,$type) {
        try{ 
        foreach($type as $key => $t){    
            if($key == 'currentasset'){$t = "'Current Asset' OR WHERE type = 'Bank'";}
            $acctnames[$key] = $dbh->query("SELECT acctname FROM sys_coa WHERE type = '$t' ORDER BY acctname ASC")->fetchall(PDO::FETCH_COLUMN);
        }
        }catch(PDOException $pdoe){
            $this->setError('get account no query failed: '.$pdoe->getMessage());
        } 
        return $acctnames;
    }
    
    public function getCategoryBalances($catVar,$subheader,$accountnames,$input,$dbh,$regbal) {
        $this->csv[] = [$subheader];
        foreach($accountnames as $acct){
            $at = ['Current Asset','Fixed Asset','Current Liability','Long-Term Liability','Equity'];
            if(in_array($subheader,$at)){
            $bal = $this->getBalanceSheetBalance($dbh,$input,$acct,$regbal);
            }else{
            $bal = $this->getBalance($dbh,$input,$acct,$regbal);
            }
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $acctTotal[] = $bal;
                $this->csv[] = ['',$acct,$this->numberFormat($bal)];
            }
        }
        if(is_array($acctTotal)){ $this->$catVar = array_sum($acctTotal);}
        $this->csv[] = ['','Total '.$subheader,$this->$catVar];
    }
    
    public function getBalance($dbh,$input,$acctname,$regbal) {
        extract($input);
        try{
        $trans = $dbh->prepare("SELECT acctname, SUM(credit) AS crbal, SUM(debit) AS drbal FROM sys_gl WHERE acctname = :acctname AND date BETWEEN :startdate AND :enddate GROUP BY acctname");
        $trans->execute(['acctname'=>$acctname,'startdate'=>$startdate,'enddate'=>$enddate]);
        $list = $trans->fetch(PDO::FETCH_ASSOC);
        $bal = $this->calcBal($list['drbal'], $list['crbal'], $regbal);
        }catch(PDOException $pdoe){
            $this->setError('get transaction query failed: '.$pdoe->getMessage());
        }
        return $bal;
    }
    
    public function getBalanceSheetBalance($dbh,$input,$acctname,$regbal) {
        extract($input);
        try{
        $trans = $dbh->prepare("SELECT acctname, SUM(credit) AS crbal, SUM(debit) AS drbal FROM sys_gl WHERE acctname = :acctname AND date <= :enddate GROUP BY acctname");
        $trans->execute(['acctname'=>$acctname,'enddate'=>$enddate]);
        $list = $trans->fetch(PDO::FETCH_ASSOC);
        $bal = $this->calcBal($list['drbal'], $list['crbal'], $regbal);
        }catch(PDOException $pdoe){
            $this->setError('get transaction query failed: '.$pdoe->getMessage());
        }
        return $bal;
    }
    
    public function calcBal($drbal,$crbal,$regbal) {
        if($regbal == 'creditbal'){
            $bal = $crbal - $drbal;
        }elseif($regbal == 'debitbal'){
            $bal = $drbal - $crbal;
        }
        return $bal;
    }
    
    public function numberFormat($bal) {
       return $bal;
    }
 
}

?>

