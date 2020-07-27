<?php 

require_once 'class.base.php';

class livesearch extends base {

    public function search($input,$dbh) {
        extract($input);
        $query = $this->setQuery($id);
        $output = "";   
        $list = $dbh->prepare($query);
        try{
        $list->execute(['val'=>'%'.$val.'%']);
        $option = $list->fetchall(PDO::FETCH_COLUMN);
           foreach($option as $value) {
            $output .= '<p>'.$value.'</p>'; 
          }
        }catch(PDOException $pdoe){
               $this->setError('Query failed: '.$pdoe->getMessage());
        }

        if($output){$this->output = $output;}else{$this->output = 'No Results Found';}
    }

    private function setQuery($id) {
        switch($id) {
            case 'acctname':
            case 'acctname[]':
            case 'cor';    
                $query = "SELECT acctname FROM sys_coa WHERE LOWER(acctname) LIKE LOWER(:val) LIMIT 5";
            break;

            case 'acctno':
            case 'acctno[]';
            case 'cor_acctno';
                $query = "SELECT acctno FROM sys_coa WHERE CAST(acctno AS TEXT) LIKE :val LIMIT 5";
            break;

            case 'bankname':
                $query = "SELECT acctname FROM sys_coa WHERE type = 'Bank' AND LOWER(acctname) LIKE LOWER(:val) LIMIT 5";
            break;

            case 'bankno':
                $query = "SELECT acctno FROM sys_coa WHERE type = 'Bank' AND CAST(acctno AS TEXT) LIKE :val LIMIT 5";
            break;

            case 'customer':
                $query = "SELECT name FROM customers WHERE LOWER(name) LIKE LOWER(:val) LIMIT 5";
            break;

            case 'customerid':
                $query = "SELECT id FROM customers WHERE CAST(id AS TEXT) LIKE :val LIMIT 5";
            break;

            case 'supplier':
                $query = "SELECT name FROM suppliers WHERE LOWER(name) LIKE LOWER(:val) LIMIT 5";
            break;

            case 'supplierid':
                $query = "SELECT id FROM suppliers WHERE CAST(id AS TEXT) LIKE :val LIMIT 5";
            break;
            
            case 'propSearchID':
                $query = "SELECT id FROM suppliers WHERE CAST(id AS TEXT) LIKE :val LIMIT 5";
            break;
        }
        return $query;
    }
    
        public function fill($input,$dbh) {
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

        if(!(is_null($result))){$this->output = ['autofill'=>$result];}else{$this->output = ['autofill'=>'error'];}
    }

    private function setFillQuery($id) {
        switch($id) {
            case 'acctname':
            case 'acctname[]':
            case 'cor';    
                $query = "SELECT acctno FROM sys_coa WHERE LOWER(acctname) = LOWER(:val) ";
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
}
?>