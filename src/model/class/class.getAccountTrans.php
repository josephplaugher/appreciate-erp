<?php

require 'class.table.php';

class getAccountTrans extends table {
    
    public $view;
    public $testval;
    
    public function getTestVal(){
        return $this->testval;
    }
    
    public function accountData($input, $dbh) {
        extract($input);
        $headers = array('Date','Description','Payee/Payer', 'Payee/Payer ID','Debit','Credit');
        try{
        $list = $dbh->prepare('SELECT date, description,payee_payer,payee_payer_id,debit,credit FROM sys_gl'
                . ' WHERE acctno = :acctno AND date BETWEEN :startdate AND :enddate ORDER BY transid DESC, acctno DESC, debit DESC');
        $list->execute(['acctno'=>$acctno,'startdate'=>$startdate,'enddate'=>$enddate]);
        $data = $list->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('query failed: '.$pdoe->getMessage());
        }
        $output = $this->openTable();
        $output .= $this->basicHeader($headers);
        $output .= $this->basicData($data,'credit','coa'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->output = $output;
    }
    
    public function trialBal($input, $dbh) {
        $this->formtitle = 'Trial Balance';
        $this->headers = array('Account No','Account Name','Debit','Credit');
        try{
        $list = $dbh->query("SELECT acctname, SUM(debit) as deb, SUM(credit) as cred FROM sys_gl 
            GROUP BY acctname ")->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('query failed: '.$pdoe->getMessage());
        }
        $this->testval = $list;
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'trial'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->output = $output;
    }
    
}
?>