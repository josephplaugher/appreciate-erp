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
            
            case 'prop-id':
                $query = "SELECT id FROM props WHERE CAST(id AS TEXT) LIKE (:val) LIMIT 5";
            break;

            case 'prop-ein':
                $query = "SELECT ein FROM entity WHERE ein LIKE (:val) LIMIT 5";
            break; 

            case 'prop-entity':
                $query = "SELECT entity FROM props WHERE ein LIKE (:val) LIMIT 5";
            break; 
            
            case 'prop-shortname':
                $query = "SELECT shortname FROM props WHERE LOWER(shortname) LIKE LOWER(:val) LIMIT 5";
            break;
            
            case 'prop-street':
                $query = "SELECT DISTINCT(street) AS street FROM props WHERE LOWER(street) LIKE LOWER(:val) LIMIT 5";
            break;
        
            case 'prop-city':
                $query = "SELECT DISTINCT(city) AS city FROM props WHERE LOWER(city) LIKE LOWER(:val) LIMIT 5";
            break;
        
            case 'prop-state':
                $query = "SELECT DISTINCT(state) AS state FROM props WHERE LOWER(state) LIKE LOWER(:val) LIMIT 5";
            break;
        
            case 'prop-zip':
                $query = "SELECT DISTINCT(zip) AS zip FROM props WHERE CAST(zip AS TEXT) LIKE (:val) LIMIT 5";
            break;
        }
        return $query;
    }
    
    private function propQuery($id) {
        switch($id) {
            case 'prop_id':
                $query = "SELECT id FROM props WHERE CAST(id AS TEXT) LIKE (:val) LIMIT 5";
            break;

            case 'prop_ein':
                $query = "SELECT ein FROM entity WHERE ein LIKE (:val) LIMIT 5";
            break; 

            case 'prop_entity':
                $query = "SELECT entity FROM props WHERE ein LIKE (:val) LIMIT 5";
            break; 
            
            case 'prop_shortname':
                $query = "SELECT shortname FROM props WHERE shortname LIKE (:val) LIMIT 5";
            break;
            
            case 'prop_street':
                $query = "SELECT street FROM props WHERE street LIKE (:val) LIMIT 5";
            break;
        
            case 'prop_city':
                $query = "SELECT city FROM props WHERE city LIKE (:val) LIMIT 5";
            break;
        
            case 'prop_state':
                $query = "SELECT state FROM props WHERE state LIKE (:val) LIMIT 5";
            break;
        
            case 'prop_state':
                $query = "SELECT state FROM props WHERE state LIKE (:val) LIMIT 5";
            break;
        }
        return $query;
    }
        
      
}
?>