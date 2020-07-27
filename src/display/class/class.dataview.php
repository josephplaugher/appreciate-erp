<?php

require_once 'class.formelements.php';

class dataview extends formelements {
       
    public function __construct() {
        $this->css = ['displayform'];
    }
        
    public function users($dbh,$id) {   
	$list = $dbh->query("SELECT id, lname, fname, position, salary, status, city, state,"
                . " street, zip, email, startdate FROM users WHERE id = '$id' ")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm('View User | '.$list['id'],$this->formname);
        $output .= $this->input('Last Name',$list['lname'],'lname','readonly');
        $output .= $this->input('First Name',$list['fname'],'fname','readonly');
        $output .= $this->input('Position',$list['position'],'position','readonly');
        $output .= $this->input('Street',$list['street'],'street','readonly');
        $output .= $this->input('City',$list['city'],'city','readonly');
        $output .= $this->input('State',$list['state'],'state','readonly');
        $output .= $this->input('Zip',$list['zip'],'zip','readonly');
        $output .= $this->input('Email',$list['email'],'email','readonly');
        $output .= $this->input('Salary',$list['salary'],'salary','readonly');
        $output .= $this->input('Startdate',$list['startdate'],'startdate','readonly');
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
        
    public function coa($dbh,$id) {
        $list = $dbh->query("SELECT acctno, acctname, description, type, status FROM sys_coa WHERE acctno = $id ")->fetch(PDO::FETCH_ASSOC);
	$this->JS =['accounts'];
        $this->css =['accounts','table','scroll'];
        if($list['status'] == 'inactive'){$inactive = '[Inactive]';}
        $output = $this->openDiv('accounts', '');
            $output .= $this->beginForm('Account Details | '.$list['acctno'].' '.$inactive,'class=newacct&method=update_acct');
                $output .= $this->input('Account Name',$list['acctname'],'acctname','readonly');
                $output .= $this->input('Description',$list['description'],'description','readonly');
                $output .= $this->input('Account Number',$list['acctno'],'acctno','readonly');
                $output .= $this->input('Account Type',$list['type'],'accttype','readonly');
                $output .= $this->input('Status',$list['status'],'status','readonly');     
                $output .= $this->openDiv('buttondiv',NULL);
                    $output .= $this->button('edit','Edit',NULL);
                    $output .= $this->button('inactive','Change Status',NULL);
                    $output .= $this->button('delete','Delete Account',NULL);
                    $output .= $this->button('close','Close',NULL);
                $output .= $this->closeDiv();
            $output .= $this->closeForm();
        $output .= $this->closeDiv();        
        
        $output .= $this->openDiv('transcontainer','');
            $output .= $this->openDiv('transfilter','commonform');
                $output .= $this->input('Start Date','','startdate',NULL);
                $output .= $this->input('End Date','','enddate',NULL);
                $output .= $this->button('getTrans','Get Transactions',NULL);
            $output .= $this->closeDiv();
        $output .= $this->openDiv('scrollcontainer','');
            $output .= $this->openDiv('scroll','');
            $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        
        $this->view = $output;
    }
    
    public function entity($dbh, $id) {  
        $list = $dbh->query("SELECT ein, name, type, taxation, function, street, city, state, zip  FROM entity WHERE ein = '$id' ")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm($this->formtitle,'class=dataentry&method=add_entity');
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
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }

     public function invoices($dbh,$id){
        $this->JS = ['invoiceCredit'];
        $list = $dbh->query("SELECT invnum, creditnum, invdate, invdue, total, customerid, customer, status"
                . " FROM invoices WHERE invnum = '".$id."' OR creditnum = '$id' AND header = 't'")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm('Invoices/Credits',$this->formname);
        if($list['invnum']){
            $output .= $this->input('Invoice Number',$list['invnum'],'invnum',NULL);
            $output .= $this->input('Total', number_format($list['total'],2),'total',NULL);
        }elseif($list['creditnum']){
            $output .= $this->input('Credit Number',$list['creditnum'],'creditnum',NULL);
            $output .= $this->input('Total',-number_format($list['total'],2),'total',NULL);
        }
        $output .= $this->input('Invoice Date',$list['invdate'],'invdate',NULL);
        $output .= $this->input('Customer',$list['customer'],'customer',NULL);
        $output .= $this->input('Status',$list['status'],'status',NULL);
        $output .= $this->input('Comment','','comments',NULL);
        $output .= $this->hiddenField('admin',$_SESSION['administrator'],'admin');
        $output .= $this->openDiv('lines-items', NULL);
        $output .= $this->openDiv('lines', NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->button('void','Void',NULL);
        $output .= $this->button('lineitems','Line Items',NULL);
        $output .= $this->closeDiv();
        
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function payment(){
        $output = $this->beginForm($this->formtitle,$this->formname);
        $output .= $this->input('Document No',$this->today,'docno',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date','','date',NULL);
        $output .= $this->input('Transaction Type','','transtype',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->input('Apply to Invoice','','invnum',NULL);
        $output .= $this->input('Comment','','comments',NULL);

        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function customer($dbh,$id) {   
        $list = $dbh->query("SELECT id, name, contact, industry, phone, email, street, city, state, zip  FROM customers WHERE id = '$id' ")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm('View Customer | '.$list['id'],'class=dataentry&method=update_customer');
        $output .= $this->input('Company Name',$list['name'],'name','readonly');
        $output .= $this->input('Industry',$list['industry'],'industry','readonly');
        $output .= $this->input('Contact Name',$list['contact'],'contact','readonly');
        $output .= $this->input('Phone',$list['phone'],'phone','readonly');
        $output .= $this->input('Email',$list['email'],'email','readonly');
        $output .= $this->input('Street',$list['street'],'street','readonly');
        $output .= $this->input('City',$list['city'],'city','readonly');
        $output .= $this->input('State',$list['state'],'state','readonly');
        $output .= $this->input('Zip',$list['zip'],'zip','readonly');
        $output .= $this->hiddenField('id',$list['id'],'id');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
     public function supplier($dbh,$id) {   
        $list = $dbh->query("SELECT id, name, contact, industry, phone, email, street, city, state, zip FROM suppliers WHERE id = '$id' ")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm('View Supplier | '.$list['id'],'class=dataentry&method=update_supplier');
        $output .= $this->input('Company Name',$list['name'],'name','readonly');
        $output .= $this->input('Industry',$list['industry'],'industry','readonly');
        $output .= $this->input('Contact Name',$list['contact'],'contact','readonly');
        $output .= $this->input('Phone',$list['phone'],'phone','readonly');
        $output .= $this->input('Email',$list['email'],'email','readonly');
        $output .= $this->input('Street',$list['street'],'street','readonly');
        $output .= $this->input('City',$list['city'],'city','readonly');
        $output .= $this->input('State',$list['state'],'state','readonly');
        $output .= $this->input('Zip',$list['zip'],'zip','readonly');
        $output .= $this->hiddenField('id',$list['id'],'id');
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function bills($dbh,$id){
        $list = $dbh->query("SELECT invnum, duedate, invdate, terms, total, supplierid, supplier, acctname, acctno, status FROM bills WHERE invnum = '".$id."' AND header ='t' ")->fetch(PDO::FETCH_ASSOC);
        $this->formtitle = 'Enter Supplier Invoice';
        $this->JS = ['invoices','livesearch'];
        $output = $this->beginForm('View Supplier Invoice','class=apentries&method=pay_supplier_invoice');
        $output .= $this->input('Invoice Number',$list['invnum'],'invnum','readonly');
        $output .= $this->input('Payment Status',$list['status'],'status','readonly');
        $output .= $this->input('Invoice Date',$list['invdate'],'date','readonly');
        $output .= $this->input('Total',$list['total'],'total','readonly');
        $output .= $this->input('Terms',$list['terms'],'terms','readonly');
        $output .= $this->input('Suppler',$list['supplier'],'supplier','readonly');
        $output .= $this->input('Supplier ID',$list['supplierid'],'supplierid','readonly');
        $output .= $this->input('Expense Account Name',$list['acctname'],'acctname','readonly');
        $output .= $this->input('Expense Account Number',$list['acctno'],'acctno','readonly');
        if($list['status'] == 'Unpaid') {
            $output .= $this->para('<br><strong>Enter Payment</strong>', 'label');
            $output .= $this->input('Bank Name','','bankname',NULL);
            $output .= $this->input('Bank Number','','bankno',NULL);
            $output .= $this->input('Amount to Pay','','amount',NULL);
            $output .= $this->input('Document Number','','docno',NULL);
        }
        $output .= $this->openDiv('buttondiv',NULL);
        if($list['status'] == 'Paid') {
            $output .= $this->button('payments','View Payment',NULL);
        }elseif($list['status'] == 'Unpaid') {
        $output .= $this->button('ajaxsubmit','Pay Invoice',NULL);
        }
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function enter_withdrawal(){
        $output = $this->beginForm($this->formtitle,$this->formname);
        $output .= $this->input('Document Number','','docno',NULL);
        $output .= $this->input('Description','','description',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Suppler','','supplier',NULL);
        $output .= $this->input('Supplier ID','','supplierid',NULL);
        $output .= $this->input('Account Name','','acctname',NULL);
        $output .= $this->input('Account Number','','acctno',NULL);
        $output .= $this->input('Transaction Type','','transtype',NULL);
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function enter_deposit(){
        $output = $this->beginForm($this->formtitle,$this->formname);
        $output .= $this->input('Document Number','','docno',NULL);
        $output .= $this->input('Description','','description',NULL);
        $output .= $this->input('Amount','','amount',NULL);
        $output .= $this->input('Date',$this->today,'date',NULL);
        $output .= $this->input('Customer','','customer',NULL);
        $output .= $this->input('Customer ID','','customerid',NULL);
        $output .= $this->input('Account Name','','acctname',NULL);
        $output .= $this->input('Account Number','','acctno',NULL);
        $output .= $this->input('Transaction Type','','transtype',NULL);
        
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('edit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    public function recs($dbh,$id){
        $list = $dbh->query("SELECT bankname,bankno,stmtenddate,stmtendbal,clearedbal,time "
                . "FROM bankrec ORDER WHERE bankno = '$no' AND WHERE stmtenddate = '$enddate' ")->fetch(PDO::FETCH_ASSOC);
        $output = $this->beginForm($this->formtitle,'');
        $output .= $this->input('Bank Name','','bankname',NULL);
        $output .= $this->input('Bank Number','','bankno',NULL);
        $output .= $this->input('Statement Date','','stmtenddate',NULL);
        $output .= $this->input('Statement Balance','','stmtendbal',NULL);
        $output .= $this->input('Reconciled Balance','','recbal',NULL);
        $output .= $this->input('Date Reconciled','','date',NULL);
                
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('delete','Delete',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
}
?>