<?php
require_once 'class.base.php';

class journalentry extends base {
    
      
    public function journal_entry($input,$dbh) {
        extract($input);
        $this->checkJournAccounts($input,$dbh);
        $inbal = $this->checkDebitCreditBal($input);
        if(($inbal === true) && is_null($this->getErrors())) {
            $input['journ_num'] = $this->generateId('journ_num', $dbh);
            
            $debit = $dbh->prepare("INSERT INTO sys_gl (journ_num, transtype,  date,  time,    description, debit,  acctno,   acctname, empid) 
                                                 VALUES (:journ_num,   'JE',   :date,DEFAULT, :description,  :amt, :acctno, :acctname, :empid)");

            $credit = $dbh->prepare("INSERT INTO sys_gl (journ_num, transtype,  date,  time,    description, credit, acctno,   acctname, empid) 
                                                 VALUES (:journ_num,   'JE',   :date,DEFAULT, :description,  :amt, :acctno, :acctname, :empid)");
            $c = 0;
            foreach ($dorc as $dorc) {  
                 if ($dorc == 'debit') { $this->bindVal($input,$debit,$c);}
                 if ($dorc == 'credit') { $this->bindVal($input,$credit,$c);}        
            $c ++;
            }
            if(is_null($this->getErrors())){
                $msg = "Journal entry ".$input['journ_num']." created successfully";
                $this->output = ['success' => $msg];
            }
        }else{
            $this->setError('Total debits do not equal total credits, or there are incomplete lines');
        }
    }
    
    private function checkJournAccounts($input,$dbh) {
        $i = 0;
        foreach($input['acctno'] as $acctno) {
            $valid = $this->checkAccounts($acctno,$input['acctname'][$i],$dbh);
            $i ++;
        }
    }
    public function checkDebitCreditBal($input) {
        extract($input);
        $c = 0;
        foreach ($acctno as $acct) {
            if (!empty($acct) && !empty($acctname[$c]) && !empty($amt[$c])  && !empty($dorc[$c])) {
            if ($dorc[$c] == 'debit') {$debitcheck[] = $amt[$c];}
            if ($dorc[$c] == 'credit') {$creditcheck[] = $amt[$c];}
            }
            $c ++;
            }
        $debit_total = array_sum($debitcheck);
        $credit_total = array_sum($creditcheck);

        //Set the variable that tells the query to execute if debits equal credits
        if ($debit_total == $credit_total) {
            return true;
        }
    }
    
    private function bindVal($input,$handler,$c) {
        extract($input);     
        try{
        $handler->bindValue('journ_num',$journ_num);
        $handler->bindValue('date',$date);
        $handler->bindValue('description',$description);
        $handler->bindValue('amt',$amt[$c]);
       // if($debcred == 'debit') { $handler->bindValue('amt',$amt);};
        //if($debcred == 'credit'){ $handler->bindValue('amt',$amt);};
        $handler->bindValue('acctno', $acctno[$c]);
        $handler->bindValue('acctname', $acctname[$c]);
        $handler->bindValue('empid', $empid);
        $handler->execute();
        }catch(PDOException $pdoe){
            $this->setError('One or more bind calls failed: '.$pdoe->getMessage());
        }
    }    
            
}

?>