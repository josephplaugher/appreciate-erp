<?php

require_once 'class.formelements.php';

class bankview extends formelements {
                  
    public function ledger() {   
        $this->formtitle ='Bank Ledger';
        $this->today = date('Y-m-d');
        $this->firstofyear = date('Y-01-01');
        $this->css = ['table','scroll','bank'];
        $this->JS = ['livesearch'];
        $output = $this->beginForm('Bank Ledger','class=bank&method=ledger');
        $output .= $this->openDiv('filter',NULL);
        $output .= $this->input('Bank Name','','bankname','');
        $output .= $this->input('Bank Number','','bankno','');
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('recfilter',NULL);
        $output .= $this->input('Start Date',$this->firstofyear,'startdate','');
        $output .= $this->input('End Date',$this->today,'enddate','');
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        
        $output .= $this->closeDiv();//close the form div
        $output .= $this->openDiv('scrollcontainer',NULL);
        $output .= $this->openDiv('scroll',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
    
    public function reconcile() {
        $this->formtitle ='Reconcile Bank';
        $this->today = date('Y-m-d');
        $this->firstofyear = date('Y-01-01');
        $this->css = ['table','scroll','bank'];
        $this->JS = ['banking.2','livesearch'];
        $output = $this->beginForm('Bank Reconciliation','class=bank&method=reconcile');
        $output .= $this->openDiv('filter', NULL);
        $output .= $this->input('Bank Name','','bankname','');
        $output .= $this->input('Bank Number','','bankno','');
        $output .= $this->input('Statement Balance','', 'stmtendbal',NULL);
        $output .= $this->input('Statement Date','', 'stmtenddate',NULL);
        $output .= $this->closeDiv();

        $output .= $this->openDiv('recfilter',NULL);
        $output .= $this->input('Reconciled Balance','', 'lastrecbal', 'readonly');
        $output .= $this->input('Cleared Balance',NULL, 'clearedbal', 'readonly');
        $output .= $this->input('Difference',NULL, 'balDiff', 'readonly');
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Get Transactions',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
       
        $output .= $this->openDiv('recbutton',NULL);
        $output .= $this->button('reconcile','Reconcile',NULL);
        $output .= $this->closeDiv();
        
        $output .= $this->closeDiv();//close the form div
        $output .= $this->openDiv('scrollcontainer',NULL);
        $output .= $this->openDiv('scroll',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
           
}
?>