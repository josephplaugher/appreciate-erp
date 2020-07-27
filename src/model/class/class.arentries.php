<?php

require_once 'class.base.php';

class arentries extends base {
       
    private $totalarray;
    
    public function getTotal(){
        return $this->totalarray;
    }
    public function create_invoice($input,$dbh) {
        $input['item'] = array_filter($input['item']);
        $input['price'] = array_filter($input['price']);
        $input['quant'] = array_filter($input['quant']);
        $this->rowCheck($input);
        
        if(is_null($this->getErrors())){
            extract($input);
            $p = 0;
            foreach ($input['price'] as $price) {
                $input['line_total'][] = $this->eachLineTotal($price, $input['quant'][$p]);
                $p ++;
            }
            $input['total'] = number_format(array_sum($input['line_total']),2);
            $input['invoicenumber'] = $this->generateId('invoices_invnum_seq', $dbh);
            $input['aracctno'] = $this->getARno($dbh);
            $input['aracctname'] = 'Accounts Receivable';
            if(is_null($this->getErrors())){
                $this->invoiceHeader($input, $dbh);
                $this->invoiceLinesEntry($input, $dbh);
                $this->debitEntry($input, $dbh);
                $this->creditEntry($input, $dbh);
            }
            if(is_null($this->getErrors())){
                $msg = "Invoice ".$input['invoicenumber']." created successfully";
                $this->output = ['success' => $msg];
            }
        }
    }
        
    private function rowCheck($input) {
        extract($input);
        //check that at least one line item is entered
        if(!isset($item) && !isset($price) && !isset($quant)) {
            $this->setError('Enter at least one line item');
        }
        // check if there are any incomplete lines
        $l = 0;   
        foreach ($item as $lineitem) {
        if(!isset($lineitem) || !isset($price[$l]) || !isset($quant[$l])) {
            $this->setError('Complete all fields for each applicable line item');
        }
        $l ++;
        }
    }
    
    private function eachLineTotal($price, $quant) {
        $total = ($price * $quant);
        return $total;
        }
        
    private function invoiceHeader($input,$dbh) {
        //To create the invoice header
        $today = $_SESSION['today']; 
        extract($input);
        try{
        $header = $dbh->prepare("INSERT INTO invoices (date, invnum, invdate, description, invdue, total, customerid, customer, acctname, acctno, status, header, empid) "
                                        . "VALUES (:today, :invnum, :invdate, :description, :terms, :invoicetotal, :customerid, :customer, :acctname, :acctno, 'unpaid','t',:empid)");
        $result = $header->execute(['today'=>$today,'invnum'=>$invoicenumber,'invdate'=>$date, 'description'=>$description, 'terms'=>$terms, 'invoicetotal'=>$total,
            'customerid'=>$customerid, 'customer'=>$customer, 'acctname'=>$aracctname, 'acctno' => $aracctno,'empid'=>$empid]);
        }catch(PDOException $pdoe){
            $this->setError('Invoice header failed: '.$pdoe->getMessage());
        }
    }
    
    private function invoiceLinesEntry($input,$dbh) {
        $today = $_SESSION['today']; 
        extract($input);
        try{
        $linequery = $dbh->prepare("INSERT INTO invoices (date, invnum, invdate, customerid, customer, description, unit_price, quant, line_total) 
                                        VALUES (:today, :invoicenumber, :invdate, :customerid, :customer, :item, :price, :quant, :line_total)");   
        $i = 0;   
        foreach ($item as $item) {
        $linequery->bindValue('today',$today);
        $linequery->bindValue('invoicenumber',$invoicenumber);
        $linequery->bindValue('invdate',$date);
        $linequery->bindvalue('customerid',$customerid);
        $linequery->bindvalue('customer',$customer);
        $linequery->bindValue('item',$item);
        $linequery->bindValue('price',$price[$i]);
        $linequery->bindValue('quant',$quant[$i]);
        $linequery->bindValue('line_total',$line_total[$i]);
        $result = $linequery->execute();

        $i ++;
        }
        }catch(PDOException $pdoe){
            $this->setError('One or more bind calls failed: '.$pdoe->getMessage());
        }
    }
    
    private function getARno($dbh) {
        //grab the current AR account number
        try{
        $arno = $dbh->query("SELECT acctno FROM sys_coa WHERE acctname = 'Accounts Receivable' ")->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('AR no query failed: '.$pdoe->getMessage());
        }
        return $arno['acctno'];
    }
   
    private function debitEntry($input,$dbh) {
        extract($input);
        try{
        $debquery = $dbh->prepare("INSERT INTO sys_gl (date, time, description, docno, payee_payer, payee_payer_id, acctname, acctno, debit) 
					VALUES (:date, DEFAULT, :description, :docno, :customer, :customerid, :acctname, :acctno, :invoicetotal)");

        $debquery->execute(['date'=>$date, 'description'=>$description, 'docno'=>$invoicenumber, 'customer'=>$customer, 'customerid'=>$customerid, 'acctname'=>'Accounts Receivable',
            'acctno'=>$aracctno, 'invoicetotal'=>$total]);
        }catch(PDOException $pdoe){
            $this->setError('Debit entry query failed: '.$pdoe->getMessage());
        }
    }
    
    private function creditEntry($input,$dbh) {
        extract($input);
        try{
        $credquery = $dbh->prepare("INSERT INTO sys_gl (date, time, description, docno, payee_payer, payee_payer_id, acctname, acctno, credit) 
					VALUES (:date, DEFAULT, :description, :docno, :customer, :customerid, :acctname, :acctno, :invoicetotal)");

        $credquery->execute(['date'=>$date, 'description'=>$description, 'docno'=>$invoicenumber, 'customer'=>$customer, 'customerid'=>$customerid, 'acctname'=>$acctname,
            'acctno'=>$acctno, 'invoicetotal'=>$total]);
        }catch(PDOException $pdoe){
            $this->setError('Credit entry query failed: '.$pdoe->getMessage());
        }   
    }
    
    public function enter_deposit($input,$dbh) {
        extract($input);
        try{
        $glcred = $dbh->prepare("INSERT INTO sys_gl (docno, description,  credit, date,  payee_payer, payee_payer_id,acctname, acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :customer, :customerid,    :acctname, :acctno, :transtype, :empid)");
        $glcheck2 = $glcred->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'customer'=>$customer,'customerid'=>$customerid,'acctname'=>$acctname,
            'acctno'=>$acctno,'transtype'=>$transtype,'empid'=>$empid]);

        $gldeb = $dbh->prepare("INSERT INTO sys_gl (docno, description,    debit, date,  payee_payer, payee_payer_id,acctname,  acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :customer, :customerid,    :bankname, :bankno, :transtype, :empid)");
        $glcheck1 = $gldeb->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'customer'=>$customer,'customerid'=>$customerid,'bankname'=>$bankname,
            'bankno'=>$bankno,'transtype'=>$transtype,'empid'=>$empid]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if($glcheck1 && $glcheck2){
            $this->output = ['success'=>'Deposit entered successfully'];
        }
    }
    
    public function receive_payment($input,$dbh) {
        extract($input);
        try{
        $header = $dbh->prepare("INSERT INTO invoices (invnum, invdate, invdue, total, customerid, customer, acctname, acctno, date, status, header, empid) "
                                        . "VALUES (:invnum, :invdate, :terms, :invoicetotal, :customerid, :customer, :acctname, :acctno, :today, 'unpaid','t',:empid)");
        $result = $header->execute(['invnum'=>$invoicenumber,'invdate'=>$date,'terms'=>$terms, 'invoicetotal'=>$invoicetotal, 'customerid'=>$customerid,
            'customer'=>$customer, 'acctname'=>$acctname, 'acctno' => $acctno, 'today'=>$today,'empid'=>$empid]);
            
        $glcred = $dbh->prepare("INSERT INTO sys_gl (docno, description,  credit, date,  payee_payer, payee_payer_id,acctname, acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :customer, :customerid,    :acctname, :acctno, :transtype, :empid)");
        $glcheck2 = $glcred->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'customer'=>$customer,'customerid'=>$customerid,'acctname'=>$acctname,
            'acctno'=>$acctno,'transtype'=>$transtype,'empid'=>$empid]);

        $gldeb = $dbh->prepare("INSERT INTO sys_gl (docno, description,    debit, date,  payee_payer, payee_payer_id,acctname,  acctno,transtype, empid) 
                                           VALUES (:docno, :description, :amount, :date, :customer, :customerid,    :bankname, :bankno, :transtype, :empid)");
        $glcheck1 = $gldeb->execute(['docno'=>$docno,'description'=>$description,'amount'=>$amount,
            'date'=>$date,'customer'=>$customer,'customerid'=>$customerid,'bankname'=>$bankname,
            'bankno'=>$bankno,'transtype'=>$transtype,'empid'=>$empid]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if($glcheck1 && $glcheck2){
            $this->output = ['success'=>'Deposit entered successfully'];
        }
    }
        
    public function create_credit($input,$dbh) {
        extract($input);
        $input['creditnum'] = "CR".$this->generateId('credit_num_sequence', $dbh);
        $invoiceNumList = $this->invoiceNumList($invoices);
        $invoiceSumList = $this->invoiceSumList($invoices);
        $input['$creditinvnumlist'] = implode(',',$invoiceNumList);
        
        try{
        $acctnoGet = $dbh->prepare("SELECT acctno FROM invoices WHERE invnum = :invnum AND header = 't'");
        $acctnoGet->execute(['invnum'=>$invnum]);
        $acctno = $acctnoGet->fetch(PDO::FETCH_ASSOC);
        $acctno = $acctno['acctno'];
        
        $acctnameGet = $dbh->prepare("SELECT acctname FROM sys_coa WHERE acctno = :acctno");
        $acctnameGet->execute(['acctno'=>$acctno]);
        $acctname = $acctnameGet->fetch(PDO::FETCH_ASSOC);
        $acctname = $acctname['acctname'];
        }catch(PDOException $pdoe){
            $this->setError('get acctnos or acctname query failed: '.$pdoe->getMessage());
        } 
        if(is_null($this->getErrors())){
            $this->creditHeader($input, $dbh);
            if($invoices[0] !== 'dna'){ $this->creditLines($input, $invoiceNumList, $invoiceSumList, $dbh);}
            $this->glCreditEntries($input,$dbh);
            }            
        if(is_null($this->getErrors())){
                $msg = "Credit ".$input['creditnum']." created successfully";
                $this->output = ['success' => $msg];
            }
    }
    
    public function invoiceNumList($invoices) {
        foreach($invoices as $invoice){
            $inv = explode(':', $invoice);
            $invoicelist[] = $inv[0];
        }
        return $invoicelist;
    }
    
    public function invoiceSumList($invoices) {
        foreach($invoices as $invoice){
            $inv = explode(':', $invoice);
            $invoicelist[] = $inv[1];
        }
        return $invoicelist;
    }
    
    public function creditHeader($input,$dbh) {
        extract($input);
        try{
        $ar = $dbh->prepare("INSERT INTO invoices (description, creditnum, creditinvnumlist, total, invdate, customerid, customer, acctname, acctno, header, empid) 
                                      VALUES (:description, :creditnum, :creditinvnumlist, :total, :date, :customerid, :customer, :acctname, :acctno, 't', :empid)"); 
        $ar->execute(['description'=>$description,'creditnum'=>$creditnum,'creditinvnumlist'=>$creditinvnumlist,'total'=>-($total),'date'=>$date, 'customerid'=>$customerid,'customer'=>$customer, 
            'acctname'=>$acctname,'acctno'=>$acctno,'empid'=>$empid]);
        }catch(PDOException $pdoe){
            $this->setError('credit header query failed: '.$pdoe->getMessage());
        } 
    }
    
    public function creditLines($input, $invnums, $invsums, $dbh) {
        extract($input);
        try{
        $inv = $dbh->prepare("INSERT INTO invoices (date, creditnum, invnum, invdate, customerid, customer, unit_price, quant, line_total) 
                                        VALUES (DEFAULT, :creditnum, :invnum, :date, :customerid, :customer, 1,1,:line_total)");
        $i = 0;
        foreach($invnums as $invnum){
            $inv->execute(['date'=>$date,'creditnum'=>$creditnum,'invnum'=>$invnum,'date'=>$date,'customerid'=>$customerid,'customer'=>$customer,'line_total'=>-($invsums[$i])]);
            $i ++;
        }
        }catch(PDOException $pdoe){
            $this->setError('one or more credit lines query failed: '.$pdoe->getMessage());
        } 
    }
    
    public function glCreditEntries($input,$dbh) {
        $input['aracctno'] = $this->getARno($dbh);
        $input['aracctname'] = 'Accounts Receivable';
        extract($input);
        try{
        $gldeb = $dbh->prepare("INSERT INTO sys_gl (date, time, description, payee_payer, payee_payer_id, docno, acctname, acctno, debit, empid) "
                . "VALUES (:date, DEFAULT, :comment, :customer, :customerid, :creditnum, :acctname, :acctno, :amount, :empid)");
        $gldeb->execute(['date'=>$date, 'comment'=> $comment, 'customer'=>$customer,  'customerid'=>$customerid, 'creditnum'=>$creditnum, 
            'acctname'=>$acctname, 'acctno'=>$acctno, 'amount'=>$total, 'empid'=>$empid]);
        
        $glcred = $dbh->prepare("INSERT INTO sys_gl (date, time, description, payee_payer, payee_payer_id, docno, acctname, acctno, credit, empid) "
                . "VALUES (:date, DEFAULT, :comment, :customer, :customerid, :creditnum, :acctname, :acctno, :amount, :empid)");
        $glcred->execute(['date'=>$date, 'comment'=> $comment, 'customer'=>$customer,  'customerid'=>$customerid, 'creditnum'=>$creditnum, 
            'acctname'=>$aracctname, 'acctno'=>$aracctno, 'amount'=>$total, 'empid'=>$empid]);
        
        
        }catch(PDOException $pdoe){
            $this->setError('a gl query failed: '.$pdoe->getMessage());
        } 
    }
}

?>