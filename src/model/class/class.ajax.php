<?php

require_once 'class.base.php';

class ajax extends base {
    
    public $input;
      
    public function changeAccountStatus($input,$dbh) {
        extract($input);
        if($status == 'Inactive'){$newstatus = 'Active';}elseif($status == 'Active'){$newstatus = 'Inactive';}
        try{
        $query = $dbh->prepare("UPDATE sys_coa SET status = :newstatus WHERE acctno = :acctno");
        $query->execute([ 'newstatus'=>$newstatus,'acctno'=>$acctno]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        $this->output = ['status'=>$newstatus];   
    }
    
    public function checkTransHistory($input,$dbh) {
        extract($input);
        $trans = $dbh->prepare("SELECT acctname, SUM(credit) AS crbal, SUM(debit) AS drbal FROM sys_gl WHERE acctno = :acctno GROUP BY acctno");
        $trans->execute(['acctno'=>$acctno]);
        $list = $trans->fetch(PDO::FETCH_ASSOC);
        if($list['drbal'] > 0.00 || $list['crbal'] > 0.00) {
            $this->setError('There are already transactions on this account so it cannot be deleted. Set it to Inactive if you want to prevent charges');
        }else{
            $dbh->query('DELETE FROM sys_coa WHERE acctno = :acctno');
        }
    }
    
    public function deleteAcct($dbh) {
        $acctno = $_POST['acctno'];
        $delete = $dbh->prepare("DELETE FROM sys_coa WHERE acctno = ?");
        $delete->execute([$acctno]);

        header('location: chartofaccounts.php');
    }
    
    public function getBankRec($input, $dbh) {
        $bankno = $input['bankno'];
        try{   
        $rec = $dbh->prepare('SELECT clearedbal FROM bankrec WHERE bankno = :bankno ORDER BY stmtenddate DESC LIMIT 1');
        $rec->execute(['bankno'=>$bankno]);
        $result = $rec->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if(!is_null($result['clearedbal'])){
        $output = $result['clearedbal'];
        }else{ $output = 0;
        }
        $this->output = ['bal'=>$output];      
    }
    
    public function reconcileBank($input, $dbh) {
        extract($input);
        $clred = $dbh->prepare('UPDATE sys_gl SET (clr) = (:stmtenddate) WHERE transid = :transid ');
        try{   
        foreach($transids as $id){
            $clred->execute(['stmtenddate'=>$stmtenddate,'transid'=>$id]);
        }
        $rec = $dbh->prepare('INSERT INTO bankrec (bankname,bankno,stmtendbal,clearedbal,stmtenddate,empid) 
                                            VALUES (:bankname,:bankno,:stmtendbal,:clearedbal,:stmtenddate,:empid)');
        $rec->execute(['bankname'=>$bankname,'bankno'=>$bankno,'stmtendbal'=>$stmtendbal,
            'clearedbal'=>$clearedbal,'stmtenddate'=>$stmtenddate,'empid'=>$id]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        $this->output = ['success'=>'Reconciliation Complete'];   
         
         
    }

    public function addUserAccess($input, $dbh) {
        $empid = $items[0];
        $area = $items[1];
        $area = str_replace(" ","_",$area);

        $query = $dbh->prepare("UPDATE access SET $area = '1' WHERE empid = ?");
        $query->execute([$empid]);
    }
    
    public function removeUserAccess($dbh, $crit) {
        $empid = $items[0];
        $area = $items[1];
        $area = str_replace(" ","_",$area);

        $query = $dbh->prepare("UPDATE access SET $area = NULL WHERE empid = ?");
        $query->execute([$empid]);
    }
    
    public function markTransCleared($input, $dbh) {
        $data = explode(':',$input['transinfo']);
        $id = $data[0];
	try{
        $clear = $dbh->prepare("UPDATE sys_gl SET clr = 'clr' WHERE transid = ?");
        $clear->execute([$id]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
 
        $this->output = ['status'=>'cleared'];      
    }
    
    public function markTransUncleared($input, $dbh) {
        $data = explode(':',$input['transinfo']);
        $id = $data[0];
	try{
        $clear = $dbh->prepare("UPDATE sys_gl SET clr = NULL WHERE transid = ?");
        $clear->execute([$id]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        $this->output = ['status'=>'uncleared'];      
    }
        
    public function getAPBills($dbh, $crit) {
        $total_query = $dbh->query("SELECT invnum, SUM(amt) as total FROM bills WHERE supplierid = '$id' GROUP BY invnum")->fetchall();
        //need to figure out how to group things with pdo
        foreach($total_query as $totalrows){	
            $invoice = $totalrows['invnum'];
            $total = $totalrows['total'];
            print "<option value=$invoice>Inv: $invoice; Total Due: $total</option>";
            print "</select>";    
        }
    }
    
    public function getPromo($input) {
        extract($input);
        try {    
        require 'stripe/init.php';  
        require 'stripe/appreciateco/keys.php';
        \Stripe\Stripe::setApiKey($SECRET_KEY);    
        $promotion = \Stripe\Coupon::retrieve($promo);
        if(!$promotion){throw new Exception('Could not connect to stripe');}
        }catch (\Stripe\Error\InvalidRequest $e) {
            if($e){
            $this->setError('Sorry, that promotional code is either expired or invalid.');
            } 
        }catch(Exception $f){
            $this->setError($f->getMessage());
        }
        $this->output = ['success'=>'discount','percentoff'=>$promotion->percent_off];
    }
    
    
 
}

?>