<?php

require_once 'class.base.php';

class apentries extends base {
    
    public function enter_withdrawal($input,$dbh) {
        extract($input);
        try{
        $glcred = $dbh->prepare("INSERT INTO sys_gl (docno, description,    credit, date,  payee_payer, payee_payer_id,acctname,  acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :supplier, :supplierid,    :bankname, :bankno, :transtype, :empid)");
        $glcheck1 = $glcred->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'supplier'=>$supplier,'supplierid'=>$supplierid,'bankname'=>$bankname,
            'bankno'=>$bankno,'transtype'=>$transtype,'empid'=>$empid]);
        
        $gldeb = $dbh->prepare("INSERT INTO sys_gl (docno, description,  debit, date,  payee_payer, payee_payer_id,acctname, acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :supplier, :supplierid,    :acctname, :acctno, :transtype, :empid)");
        $glcheck2 = $gldeb->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'supplier'=>$supplier,'supplierid'=>$supplierid,'acctname'=>$acctname,
            'acctno'=>$acctno,'transtype'=>$transtype,'empid'=>$empid]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if(($glcheck1 == 1) && ($glcheck2 ==1)){
            $this->output = ['success'=>'Withdrawal entered successfully'];
        }
    }
    
    public function enter_supplier_invoice($input,$dbh) {
        extract($input);
        $duplicate = $this->checkSupplierInvoice($supplierid, $invnum, $dbh);
        
        if($duplicate == false){
            $apacctno = $this->getAPno($dbh);
            try{
            //To enter the invoice
            $header = $dbh->prepare("INSERT INTO bills (supplier, supplierid, invnum, invdate, terms, duedate, total, acctname, acctno, comments, status, header) 
                                    VALUES (:supplier, :supplierid, :invnum, :date, :terms, :duedate, :amount, :acctname, :acctno, :comments, 'Unpaid', 't' )");
            $header->execute(['supplier'=>$supplier, 'supplierid'=>$supplierid, 'invnum'=>$invnum, 'date'=>$date,'terms'=>$terms,'duedate'=>$duedate,
                'amount'=>$amount,'acctname'=>$acctname, 'acctno'=>$acctno,'comments'=>$comments]);

            $line = $dbh->prepare("INSERT INTO bills (supplier, supplierid, invnum, amt) 
                                    VALUES (:supplier, :supplierid, :invnum, :amount)");
            $line->execute(['supplier'=>$supplier, 'supplierid'=>$supplierid, 'invnum'=>$invnum, 'amount'=>$amount]);

            //to enter the accounts payable
            $deb = $dbh->prepare("INSERT INTO sys_gl (date, time, docno, payee_payer, payee_payer_id, acctname, acctno, debit, empid) 
                                       VALUES (:date, DEFAULT, :invnum, :supplier, :supplierid, :acctname, :acctno, :amount, :empid)");
            $deb->execute(['date'=>$date,'invnum'=>$invnum,'supplier'=>$supplier, 'supplierid'=>$supplierid, 'acctname'=>$acctname, 'acctno'=>$acctno, 'amount'=>$amount,'empid'=>$empid]);

            $cred = $dbh->prepare("INSERT INTO sys_gl (date, time, docno, payee_payer,  payee_payer_id, acctname, acctno, credit, empid) 
                                       VALUES (:date, DEFAULT, :invnum, :supplier, :supplierid, 'Accounts Payable', :acctno, :amount, :empid)");
            $cred->execute(['date'=>$date,'invnum'=>$invnum,'supplier'=>$supplier, 'supplierid'=>$supplierid, 'acctno'=>$apacctno, 'amount'=>$amount,'empid'=>$empid]);
        
            }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
            }
        }
        $this->output = ['success'=>'Supplier Invoice Entered Successfully'];
      }
    
    private function checkSupplierInvoice($supplierid,$invnum,$dbh) {
        try{
        $check = $dbh->prepare("SELECT invnum FROM bills WHERE invnum = :invnum AND supplierid = :supplierid"); //remember to use single quotes if not an integer datatype
        $check->execute(['invnum'=>$invnum,'supplierid'=>$supplierid]);
        $exists = $check->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if(!empty($exists)) {
            $inv = $exists['invnum'];
            $this->setError("This invoice number has already been entered for this supplier");
        }elseif($exists == 0){return false;}
    }
    
    private function getAPno($dbh) {
        //grab the current AR account number
        try{
        $apno = $dbh->query("SELECT acctno FROM sys_coa WHERE acctname = 'Accounts Payable' ")->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('AP no query failed: '.$pdoe->getMessage());
        }
        return $apno['acctno'];
    }
    
    public function pay_supplier_invoice($input,$dbh) {
        extract($input);     
        $this->enterSupplierPmt($input,$dbh);
        $due = $this->checkBalance($input,$dbh);
        if($due == 0){
            $this->markInvoicePaid($input,$dbh);
        }
        $apsacctno = $this->getAPno($dbh);
        try{
        //to clear the accounts payable
        $deb = $dbh->prepare("INSERT INTO sys_gl (date, time, docno, payee_payer,  payee_payer_id, acctname, acctno, debit, empid) 
                                   VALUES (:date, DEFAULT, :invnum, :supplier, :supplierid, 'Accounts Payable', :acctno, :amount, :empid)");
        $deb->execute(['date'=>$date,'invnum'=>$invnum,'supplier'=>$supplier, 'supplierid'=>$supplierid, 'acctno'=>$apacctno, 'amount'=>$amount,'empid'=>$empid]);
        
        $cred = $dbh->prepare("INSERT INTO sys_gl (date, time, docno, payee_payer, payee_payer_id, acctname, acctno, credit, empid) 
                                   VALUES (:date, DEFAULT, :invnum, :supplier, :supplierid, :acctname, :acctno, :amount, :empid)");
        $cred->execute(['date'=>$date,'invnum'=>$invnum,'supplier'=>$supplier, 'supplierid'=>$supplierid, 'acctname'=>$bankname, 'acctno'=>$bankno, 'amount'=>$amount,'empid'=>$empid]);

        }catch(PDOException $pdoe){
            $this->setError('GL queries failed: '.$pdoe->getMessage());
        }
        $this->output = ['success'=>'Supplier Invoice Marked Paid'];
    }
    
    private function enterSupplierPmt($input,$dbh) {
        extract($input);
        try{
            //enter the partial payment
            $line = $dbh->prepare("INSERT INTO bills (supplier, supplierid, invnum, amt) 
                                VALUES (:supplier, :supplierid, :invnum, :amount)");
            $line->execute(['supplier'=>$supplier, 'supplierid'=>$supplierid, 'invnum'=>$invnum, 'amount'=>-($amount)]);

            }catch(PDOException $pdoe){
            $this->setError('enter payment query failed: '.$pdoe->getMessage());
            }
    }
    
    private function checkBalance($input,$dbh) {
        $checkbal = $dbh->prepare("SELECT SUM(amt) as due FROM bills WHERE supplierid = :supplierid AND invnum = :invnum AND header IS NULL");
        $checkbal->execute(['supplierid'=>$supplierid, 'invnum'=>$invnum]); 
        $bal = $checkbal->fetch(PDO::FETCH_ASSOC);
        return $bal['due'];
    }
    
    
    private function markInvoicePaid($input,$dbh) {
        extract($input);
        try{
        //the whole bill was paid, mark it paid
        $header = $dbh->prepare("UPDATE bills SET (status) = ('Paid') WHERE invnum = :invnum AND supplierid = :supplierid AND    header = 't' ");
        $header->execute(['invnum'=>$invnum, 'supplierid'=>$supplierid]);
        }catch(PDOException $pdoe){
            $this->setError('mark paid query failed: '.$pdoe->getMessage());
        }
    }
}

?>