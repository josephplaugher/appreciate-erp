<?php

require 'class.table.php';

class bank extends table {
    
public $currentbal;
public $translist;
public $unrectranslist;
public $headers = ['Date','Description','Payee/Payer','Doc no','Clr','Deposit','Withdrawal','Balance'];
public $recheaders = ['Date','Description','Payee/Payer','Doc no','Clr','Deposit','Withdrawal'];

    function ledger($input,$dbh) {
        extract($input);
        $this->currentBal($input, $dbh);
        $this->getTrans($bankno,$startdate,$enddate,$dbh);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->bankData($this->translist,$this->currentbal); //array and the key at which to terminate each table row
        $output .= $this->closeTable();
        $this->output = $output; 
    }
    
    function reconcile($input,$dbh) {
        extract($input);
        $this->getUnrecTrans($input,$dbh);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->recheaders);
        $output .= $this->recData($this->unrectranslist); //array and the key at which to terminate each table row
        $output .= $this->closeTable();
        $this->output = $output; 
    }

    function calcBal($translist, $bal) {
       $newlist =array();
       $i = 0;
        foreach($translist as $list) {
         $newlist[$i]['date'] = $list['date'];
         $newlist[$i]['description'] = $list['description'];
         $newlist[$i]['payee_payer'] = $list['payee_payer'];
         $newlist[$i]['docno'] = $list['docno'];
         $newlist[$i]['clr'] = $list['clr'];
         $newlist[$i]['debit'] = $list['debit'];
         $newlist[$i]['credit'] = $list['credit'];
         if($i == 0){
            $newlist[$i]['bal'] = $bal;
            $newbal = $bal - $list['debit'] + $list['credit'];
            }else{ 
            $newbal = $bal - $list['debit'] + $list['credit'];
            $bal = $newbal;
            $newlist[$i]['bal'] = $bal;
            }
         $i++;
        }
    return $newlist;
    }
    
    function getCurBal() {
        return $this->currentbal;
    }

    function currentBal($input,$dbh) {
        extract($input);
        try{
        $list = $dbh->prepare("SELECT SUM(debit) as debit,SUM(credit) as credit FROM sys_gl "
                . "WHERE acctno = '$bankno' AND date BETWEEN '$startdate' AND '$enddate'");
        $list->execute(['bankno'=>$bankno,'startdate'=>$startdate,'enddate'=>$enddate]);
        $result  = $list->fetchall(PDO::FETCH_ASSOC);
        $this->currentbal = ($result[0]['debit'] - $result[0]['credit']);
        }catch(PDOException $pdoe){
            $this->setError('bal query failed: '.$pdoe->getMessage());
        }
    }
  
    function getTrans($bankno,$startdate,$enddate,$dbh) {
        try{
        $list = $dbh->prepare("SELECT date, description, payee_payer, docno, clr, debit, credit FROM sys_gl 
            WHERE acctno = :bankno AND date BETWEEN :startdate AND :enddate ORDER BY date DESC");
        $list->execute(['bankno'=>$bankno,'startdate'=>$startdate,'enddate'=>$enddate]);
        
        $this->translist = $list->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
        $this->setError('trans query failed: '.$pdoe->getMessage());
        }
    }
    
    function getUnrecTrans($input,$dbh) {
        extract($input);
        try{
        $query = "SELECT transid, date, description, payee_payer, docno, clr, debit, credit "
                . "FROM sys_gl WHERE acctno = :bankno AND date <= :stmtenddate "
                . "AND (clr = 'clr' OR clr IS NULL) ORDER BY date DESC";
        $unrectranslist = $dbh->prepare($query);
        $unrectranslist->execute(['bankno'=>$bankno,'stmtenddate'=>$stmtenddate]);
        $this->unrectranslist = $unrectranslist->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
        $this->setError('trans query failed: '.$pdoe->getMessage());
        }
    }
    
    function getHeaders() {
        $headers = ['Date','Description','Payee/Payer','Doc no','Clr','Deposit','Withdrawal','Balance'];
        return $headers;
    }
   
}
?>