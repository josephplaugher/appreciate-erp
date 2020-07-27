<?php
require_once '../model/class/class.table.php';

class reportlist extends table {

    public $dbh;
    public $formtitle;
    public $headers;
    public $termkey;
    public $css;
    public $JS;
       
    public function getFormTitle() {
        return $this->formtitle;
    }
    
    public function __construct($html,$title){
        $this->css = ['font','header','table','scroll'];
        $this->JS = ['reportlist'];
        $this->view = $html->openDiv('pagecontainer',NULL);
        $this->view .= $html->openDiv('header',NULL);
        $title = $this->getListTitle($title);
        $this->view .= $html->header(str_replace('_',' ',$_SESSION['company_name'])." | ".$title);
        $this->view .= $html->closeDiv();
    }
    
    public function getListTitle($title) {
        switch($title){
            case 'coa':
                $output = $this->formtitle = 'Chart of Accounts';
                break;
            case 'gl':
                $output = $this->formtitle = 'General Ledger';
                break;
            case 'journ':
                $output = $this->formtitle = 'General Journal';
                break;
            case 'users':
                $output = $this->formtitle = 'Users';
                break;
            case 'suppliers':
                $output = $this->formtitle = 'Suppliers';
                break;
            case 'customers':
                $output = $this->formtitle = 'Customers';
                break;
            case 'invoices':
                $output = $this->formtitle = 'Accounts Receivable Invoices and Credits';
                break;
            case 'invoicelines':
                $output = $this->formtitle = 'Invoice Detail';
                break;
            case 'bills':
                $output = $this->formtitle = 'Accounts Payable Invoices';
                break;
            case 'bankrecs':
                $output = $this->formtitle = 'Bank Reconciliations';
                break;
        }
        return $output;
    }
    
    public function getView() {
        print $this->view;
    }  
    
    public function getCss() {
        return $this->css;
    }
      
    public function getJS() {
        return $this->JS;
    }
    
    public function coa($dbh,$id) {
        $this->formtitle = 'Chart of Accounts';
        $this->headers = array('Num','Name','Description','Type','Subtype');
        $list = $dbh->query('SELECT acctno, acctname, description, type, subtype FROM sys_coa order by acctno ASC, type, acctname DESC')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'coa'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
    
    public function gl($dbh, $id) {
        $this->formtitle = 'General Ledger';
        $this->headers = array('Date','TransID', 'Account No','Account Name','Trans Type','Doc No','Journ No','Description','Payee/Payer', 'Payee/Payer ID','Debit','Credit');
        $list = $dbh->query('SELECT date, transid, acctno, acctname, transtype, docno, journ_num, description,payee_payer,payee_payer_id,debit,credit FROM sys_gl '
              . 'ORDER BY date DESC, acctno DESC, debit DESC')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'gl'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
   
    public function journ($dbh,$id) {
        $this->formtitle = 'General Journal';
        $this->headers = array('Date','TransID', 'Account No','Account Name','Trans Type','Doc No','Journ No','Description','Payee/Payer', 'Payee/Payer ID','Debit','Credit');
        $list = $dbh->query('SELECT date, transid, acctno, acctname, transtype, docno, journ_num, description, payee_payer, payee_payer_id, debit, credit 
              FROM sys_gl ORDER BY date DESC, transid ASC, debit DESC')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'journ'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
    
    public function entities($dbh,$id) {
        $this->formtitle = 'Entities';
        $this->headers = array('EIN, Name, Type');
        $list = $dbh->query('SELECT ein, name, type from entity')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'entity'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
    
    public function invoices($dbh,$id) {
      $this->title = 'Accounts Receivable';
      $this->headers = array('Invoice No', 'Credit Number','Invoice Date', 'Due Date', 'Total', 'Customer ID', 'Customer', 'Status');
      $list = $dbh->query("SELECT invnum, creditnum, invdate, invdue, total, customerid, customer, status
              FROM invoices WHERE header = 't' ORDER BY invnum DESC")->fetchall(PDO::FETCH_ASSOC);
      $output = $this->openTable();
      $output .= $this->basicHeader($this->headers);
      $output .= $this->basicData($list,$id,'invoices'); //array, the key at which to terminate each table row, and code to set display name
      $output .= $this->closeTable();
      $this->view .= $output;
    }
           
    public function invoicelines($dbh,$invnum) {
      $this->title = 'Invoice '.$invnum. ', Lines Items';
      $this->headers = array('Invoice No','Credit No','Description','Price Each', 'Quantity','Line Total (CR Applied)');
      $list = $dbh->query("SELECT invnum, creditnum,description, unit_price, quant, line_total
              FROM invoices WHERE invnum = '".$invnum."' OR creditnum = '".$invnum."' AND header IS NULL ORDER BY line_total")->fetchall(PDO::FETCH_ASSOC);
      $output = $this->openTable();
      $output .= $this->basicHeader($this->headers);
      $output .= $this->basicData($list,$id,'invoicelines'); //array, the key at which to terminate each table row, and code to set display name
      $output .= $this->closeTable();
      $this->view .= $output;
    }
    
    public function customers($dbh,$id) {
        $this->formtitle = 'Customers';
        $this->headers = array('ID','Company','Contact','Industry');
        $list = $dbh->query('SELECT id, name, contact, industry FROM customers')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'customer'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
    
    public function suppliers($dbh,$id) {
        $this->formtitle = 'Suppliers';
        $this->headers = array('ID','Company','Contact','Industry');
        $list = $dbh->query('SELECT id, name, contact, industry FROM suppliers')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'supplier'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
      }
      
    public function users($dbh,$id) {
        $this->formtitle = 'Users';
        $this->headers = array('ID','Last Name','First Name','Position','Status');
        $list = $dbh->query('SELECT id, lname, fname, position, status FROM users ORDER by lname, fname DESC')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'users'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
     
    public function bills($dbh,$id) {
        $this->title = 'Supplier Invoices';
        $this->headers = ['Invoice No', 'Invoice Date', 'Due Date', 'Supplier ID', 'Supplier', 'Status '];
        $this->termkey = array_values(array_slice($this->headers, -1))[0];
        $list = $dbh->query("SELECT invnum, invdate, duedate, supplierid, supplier, status FROM bills WHERE header = 't' ORDER BY invdate ASC, invnum ASC")->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'bills'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
    
    public function bankrecs($dbh,$id) {
        $this->headers = array('Bank Name','Bank Number','Statement Date','Ending Balance','Reconciled Balance','Date Reconciled');
        $list = $dbh->query('SELECT bankname,bankno,stmtenddate,stmtendbal,clearedbal,time FROM bankrec ORDER by bankname,stmtenddate DESC')->fetchall(PDO::FETCH_ASSOC);
        $output = $this->openTable();
        $output .= $this->basicHeader($this->headers);
        $output .= $this->basicData($list,$id,'recs'); //array, the key at which to terminate each table row, and code to set display name
        $output .= $this->closeTable();
        $this->view .= $output;
    }
   
}
?>