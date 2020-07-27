<?php

class base {

public $output;
public $error;
public $id;
public $returnval;               

    function getTransList() {
        return $this->translist;
    }
    
    function returnval() {
        return $this->returnval;
    }
    
    public function setError($error) {
        $this->error = ['error'=>$error];
    }
    
    public function getErrors() {
        return $this->error;
    }
    
    public function setOutputType($class) {
        switch($class) {
          default:
          $this->returnval = json_encode($this->output);
          break;
          
          case 'bank':
          case 'livesearch':
          case 'properties':
          case 'ajaxHTML':
          case 'getAccountTrans':
          $this->returnval = $this->output;
          break;
          }
    }
        
    public function generateId($table,$dbh) {
        $id_result = $dbh->query("SELECT nextval('".$table."') AS id")->fetch(PDO::FETCH_ASSOC);
        return $id_result['id'];
    }
    
    public function verifyData($input,$dbh) {
        extract($input);
        $query = $this->setFillQuery($id);
        $output = "";   
        $list = $dbh->prepare($query);
        try{
        $list->execute(['val'=>$val]);
        $result = $list->fetch(PDO::FETCH_COLUMN);
           
        }catch(PDOException $pdoe){
               $this->setError('Query failed: '.$pdoe->getMessage());
        }

        if(is_null($result)){
        $this->setError($this->setValError($id));
        }
    }
    
    public function setValQuery($id) {
        switch($id) {
            case 'acctname':
            case 'acctname[]':
            case 'cor';    
                $query = "SELECT acctname FROM sys_coa WHERE acctname = :val ";
            break;

            case 'acctno':
            case 'acctno[]';
            case 'cor_acctno';
                $query = "SELECT acctname FROM sys_coa WHERE CAST(acctno AS TEXT) = :val ";
            break;

            case 'bankname':
                $query = "SELECT acctno FROM sys_coa WHERE type = 'Bank' AND LOWER(acctname) = LOWER(:val) ";
            break;

            case 'bankno':
                $query = "SELECT acctno FROM sys_coa WHERE type = 'Bank' AND CAST(acctno AS TEXT) LIKE :val LIMIT 5";
            break;

            case 'customer':
                $query = "SELECT id FROM customers WHERE LOWER(name) = LOWER(:val) ";
            break;

            case 'customerid':
                $query = "SELECT name FROM customers WHERE CAST(id AS TEXT) = :val ";
            break;

            case 'supplier':
                $query = "SELECT id FROM suppliers WHERE LOWER(name) = LOWER(:val) ";
            break;

            case 'supplierid':
                $query = "SELECT name FROM suppliers WHERE CAST(id AS TEXT) = :val ";
            break;
        }
        return $query;
    }
    
    public function setValError($id) {
        switch($id) {
            case 'acctname':
            case 'acctname[]':
            case 'cor';    
                $error = 'That account name is not valid';
            break;

            case 'acctno':
            case 'acctno[]';
            case 'cor_acctno';
                $query = "SELECT acctname FROM sys_coa WHERE CAST(acctno AS TEXT) = :val ";
            break;

            case 'bankname':
                $query = "SELECT acctno FROM sys_coa WHERE type = 'Bank' AND LOWER(acctname) = LOWER(:val) ";
            break;

            case 'bankno':
                $query = "SELECT acctno FROM sys_coa WHERE type = 'Bank' AND CAST(acctno AS TEXT) LIKE :val LIMIT 5";
            break;

            case 'customer':
                $query = "SELECT id FROM customers WHERE LOWER(name) = LOWER(:val) ";
            break;

            case 'customerid':
                $query = "SELECT name FROM customers WHERE CAST(id AS TEXT) = :val ";
            break;

            case 'supplier':
                $query = "SELECT id FROM suppliers WHERE LOWER(name) = LOWER(:val) ";
            break;

            case 'supplierid':
                $query = "SELECT name FROM suppliers WHERE CAST(id AS TEXT) = :val ";
            break;
        }
        return $error;
    }
    
    protected function checkAccounts($input,$dbh) {
        extract($input);
        $query = $dbh->prepare('SELECT acctname, acctno from sys_coa WHERE acctname = :acctname & WHERE acctno = :acctno');
        try{
        $list->execute(['acctname'=>$acctname,'acctno'=>$accno]);
        $result = $list->fetch(PDO::FETCH_COLUMN);
           
        }catch(PDOException $pdoe){
               $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if(is_null($result)){
        $this->setError('An invalid account name or number was entered');
        }
    }
}

?>