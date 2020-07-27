<?php

require_once 'class.formelements.php';

class formview extends formelements {
                 
    public function journal_entry() { 
        $this->css = ['invoice','journ'];
        $this->JS = ['invoices', 'livesearch'];
        $this->formtitle = 'Journal Entry';
        $output = $this->beginForm('Journal Entry','class=journalentry&method=journal_entry');
               
        $output .= $this->openDiv('invheaders', NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Description',NULL,'description',NULL);
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('lines-items', NULL);
        $output .= $this->journLinesHeader();
        $output .= $this->openDiv('lines', NULL);
        $output .= $this->journLines();
        $output .= $this->journLines();
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->button('addline','Add Line',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
                 
        
    public function add_user() {   
        $this->formtitle = 'Add User';
        $output = $this->beginForm('Add User','class=adduser');
        $output .= $this->input('Last Name','','lname','');
        $output .= $this->input('First Name','','fname','');
        $output .= $this->input('Position','','position','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        $output .= $this->input('Email','','email','');
        $output .= $this->input('Salary','','salary','');
        $output .= $this->input('Startdate',$this->today,'startdate','');
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
        
    public function add_account() {
        $this->formtitle = 'Add Account';
        $this->JS = ['accounts'];
        $output = $this->beginForm($this->formtitle,'class=newacct&method=add_account');
        $output .= $this->openDiv('', 'accounts');
        $output .= $this->input('Account Name','','acctname','');
        $output .= $this->input('Account Number','','acctno','');
        $output .= $this->input('Description','','description','');
        $output .= $this->accttype('Account Type','','accttype','');
        
        $output .= $this->openDiv('modifier','');
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function trialBal() {
        $this->formtitle = 'Trial Balance';
        $this->JS = ['accounts'];
        $this->css = ['table','scroll'];
        $output = $this->beginForm($this->formtitle,'class=getAccountTrans&method=trialBal');
            $output .= $this->input('Start Date','','startdate',NULL);
            $output .= $this->input('End Date','','enddate',NULL);
            $output .= $this->button('ajaxsubmit','Submit',NULL);
            $output .= $this->button('csv','Export to CSV',NULL);
        $output .= $this->closeForm();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('scrollcontainer','');
            $output .= $this->openDiv('scroll','');
            $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $this->view = $output;
    }
    
    public function create_invoice() { 
        $this->formtitle = 'Create Invoice';
        $this->css = ['journ','invoice'];
        $this->JS = ['invoices','livesearch'];
        $output = $this->beginForm('Create Invoice','class=arentries&method=create_invoice');
               
        $output .= $this->openDiv('invheaders', NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Description',NULL,'description',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->terms();
        $output .= $this->input('Rev Account Name',NULL,'acctname',NULL);
        $output .= $this->input('Rev Account Number',NULL,'acctno',NULL);
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('lines-items', NULL);
        $output .= $this->invoiceLinesHeader();
        $output .= $this->openDiv('lines', NULL);
        $output .= $this->invoiceLines();
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->button('addline','Add Line',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function create_credit(){
        $this->JS = ['invoiceCredit','livesearch'];
        $this->formtitle = 'Create Credit';
        $output = $this->beginForm($this->formtitle,'class=arentries&method=create_credit');
        $output .= $this->input('Credit Date',$this->today,'date',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->input('Comments','','comments',NULL);
        $output .= $this->input('Credit Total','','total',NULL);
        
        $output .= $this->openDiv('invoicelist',NULL);
        $output .= $this->multipleSelect('Invoices -> Total','selectedtotal'); //this is designed to be populated by javascript
        $output .= $this->closeDiv();
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function receive_payment(){
        $this->JS = ['livesearch'];
        $output = $this->beginForm('Receive Invoice Payment<br>the class method isnt ready','class=arentries&method=receive_payment');
        $output .= $this->input('Document Number','','docno',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Transaction Type','','transtype',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->input('Apply to Invoice','','invnum',NULL);
        $output .= $this->input('Description','','description',NULL);

        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('submit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function add_customer() {
        $output = $this->beginForm('Add Customer','class=dataentry&method=add_customer');
        $output .= $this->input('Company Name','','name','');
        $output .= $this->input('Industry','','industry','');
        $output .= $this->input('Contact Name','','contact','');
        $output .= $this->input('Phone','','phone','');
        $output .= $this->input('Email','','email','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function enter_supplier_invoice(){
        $this->JS = ['livesearch'];
        $output = $this->beginForm('Enter Supplier Invoice','class=apentries&method=enter_supplier_invoice');
        $output .= $this->input('Invoice Number','','invnum',NULL);
        $output .= $this->input('Invoice Date',$this->today,'date',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $terms = ['Net 30'=>'Net 30','Net 15'=>'Net 15','Due Upon Receipt'=>'Due Upon Receipt','Other'=>'Other'];
        $output .= $this->dropDown('Terms','terms', 'terms', $terms);
        $output .= $this->input('Due Date','','duedate',NULL);
        $output .= $this->input('Suppler','','supplier',NULL);
        $output .= $this->input('Supplier ID','','supplierid',NULL);
        $output .= $this->input('Account Name','','acctname',NULL);
        $output .= $this->input('Account Number','','acctno',NULL);

        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function enter_deposit(){
        $this->JS = ['livesearch'];
        $output = $this->beginForm('Enter Deposit','class=arentries&method=enter_deposit');
        $output .= $this->input('Document Number','','docno',NULL);
        $output .= $this->input('Description','','description',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->input('Revenue Account Name','','acctname',NULL);
        $output .= $this->input('Revenue Account Number','','acctno',NULL);
        $output .= $this->input('Bank Account Name','','bankname',NULL);
        $output .= $this->input('Bank Account Number','','bankno',NULL);
        $output .= $this->transtype('Transaction Type','','transtype',NULL);
        $output .= $this->openDiv('resultDropdown',NULL);
        $output .= $this->closeDiv();
                
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
        
    public function enter_withdrawal(){
        $this->JS = ['livesearch'];
        $output = $this->beginForm('Enter Withdrawal','class=apentries&method=enter_withdrawal');
        $output .= $this->input('Document Number','','docno',NULL);
        $output .= $this->input('Description','','description',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Supplier','','supplier',NULL);
        $output .= $this->input('Supplier ID','','supplierid',NULL);
        $output .= $this->input('Expense Account Name','','acctname',NULL);
        $output .= $this->input('Expense Account Number','','acctno',NULL);
        $output .= $this->input('Bank Account Name','','bankname',NULL);
        $output .= $this->input('Bank Account Number','','bankno',NULL);
        $output .= $this->transtype('Transaction Type','','transtype',NULL);
        $output .= $this->openDiv('resultDropdown',NULL);
        $output .= $this->closeDiv();
                
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function add_entity() {   
        $output = $this->beginForm('Add Entity','class=dataentry&method=add_entity');
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
        $this->view = $output;
    }
    
    public function add_supplier() {  
        $output = $this->beginForm('Add Supplier','class=dataentry&method=add_supplier');
        $output .= $this->input('Company Name','','name','');
        $output .= $this->input('Industry','','industry','');
        $output .= $this->input('Contact Name','','contact','');
        $output .= $this->input('Phone','','phone','');
        $output .= $this->input('Email','','email','');
        $output .= $this->input('Street','','street','');
        $output .= $this->input('City','','city','');
        $output .= $this->input('State','','state','');
        $output .= $this->input('Zip','','zip','');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
       
    public function statement($dbh,$stmt) {
        $this->formtitle = ucwords(str_replace('_',' ', $stmt));
        $class = strtolower(str_replace('_','', $stmt));
        $this->JS = ['statements2'];
        $output .= $this->beginForm($this->formtitle,'class='.$class.'&method=getStatement');

        $output .= $this->openDiv('filter', NULL);
        if($class !== 'balancesheet') {$output .= $this->input('Start Date','2017-01-01','startdate',NULL);}
        $output .= $this->input('End Date','2017-10-16','enddate',NULL);
                
        $output .= $this->openDiv('propfilter',NULL);
        $output .= $this->reportFilter($this->getEntities($dbh));
        $output .= $this->closeDiv();
        
        $opt = ['no'=>'No', 'yes'=>'Yes'];
        $output .= $this->dropDown('Include accounts with zero balance','zero','zero',$opt);
                
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Submit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
                
        $this->view = $output;
    }
    
    private function getEntities($dbh) {
        $entities = $dbh->query("SELECT ein, name FROM entity ORDER BY name DESC")->fetchall(PDO::FETCH_ASSOC);
        return $entities;
    }
   
}
?>