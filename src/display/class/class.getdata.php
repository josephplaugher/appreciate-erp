<?php
require_once 'class.baselist.php';

class dataset extends baselist {
    
    public function coa($id) {
      $this->query = "SELECT acctno, acctname, description, type FROM sys_coa WHERE acctno = '".$id."' ";
    }
    
    public function gl() {
      $this->title = 'General Ledger';
      $this->headers = array('Date','Account No','Account Name','Trans Type','Doc No','Journ No','Description','Payee/Payer','Debit','Credit');
      $this->termkey = array_values(array_slice($this->headers, -1))[0];
      $this->query = 'SELECT date, acctno, acctname, transtype, docno, journ_num, description,payee_payer,debit,credit FROM sys_gl '
              . 'ORDER BY date DESC, acctno DESC, debit DESC';
    }
   
    public function journ() {
      $this->query = 'SELECT date, acctno, acctname, transtype, docno, journ_num, description,payee_payer,debit,credit 
              FROM sys_gl ORDER BY date DESC, transid ASC, debit DESC';
    }
    
     public function inv($id) {
      $this->query = "SELECT invnum, invdate, invdue, total, customerid, customer,  status FROM invoices WHERE invnum = '".$id."' AND header = 't'";
      $this->secondaryquery = "SELECT unit_price, quant, line_total FROM invoices WHERE invnum = '".$id."' ";
     }
    
    public function invLineData($invnum,$dbh) {
      
    }
        
    public function suppliers() {
      $this->query = 'SELECT id, supplierid, supplier, industry FROM suppliers';
      }
      
    public function users($id) {
      $this->query = "SELECT id, lname, fname, position, salary, status, city, state, street, zip, email, startdate FROM users WHERE id = '".$id."' ";
    }
     
    public function customers() {
      $this->title = 'Customers';
      $this->headers = array('ID','Company','Contact','Industry');
      $this->termkey = array_values(array_slice($this->headers, -1))[0];
      $this->query = 'SELECT id, name, contact, industry FROM customers';
    }
    
    public function bills() {
      $this->title = 'Supplier Invoices';
      $this->headers = ['Date','Description','Payee/Payer','Doc no','Clr','Deposit','Withdraw'];
      $this->termkey = array_values(array_slice($this->headers, -1))[0];
      $this->query = 'SELECT invnum, duedate, invdate, supplierid, supplier_name, status FROM bills';
    }
    
    public function ledger() {
      $this->title = 'Bank Ledger';
      $this->headers = ['Date','Description','Payee/Payer','Doc no','Clr','Deposit','Withdrawal','Balance'];
      $this->termkey = array_values(array_slice($this->headers, -1))[0];
    }
    
    public function bankrecs() {
      $this->title = 'Bank Reconciliations';
      $this->headers = ['Account No','Account Name','Last Four','Stmt End Date','Stmt End Bal','Cleared Balance','Date Reconciled', 'Emp ID'];
      $this->termkey = array_values(array_slice($this->headers, -1))[0];
      $this->query = 'SELECT bankno, bankname, lastfour, stmtenddate, stmtendbal, clearedbal, time, empid FROM bankrec';
    }
}
?>