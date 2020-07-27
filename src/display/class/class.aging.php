<?php
include 'class.customers.php';

class aging extends customer {
	
        private $newest;
        private $oldest;
                
        private function setNewest($newest) {
            $this->newest = date('Y-m-d', strtotime($newest));
            return  $this->newest;
        }
        
        private function setOldest($oldest) {
            $this->oldest = date('Y-m-d', strtotime($oldest));
            return  $this->oldest;
        }
        
        public function getBalances($dbh, $newest, $oldest) {
            $newest = self::setNewest($newest);
            $oldest = self::setOldest($oldest);
            $this->dbh = $dbh; 
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $getbal = $dbh->prepare("SELECT cust_name, invdate, SUM(amt) AS amt FROM invoices WHERE invdate BETWEEN :startdate AND :enddate GROUP BY invdate, cust_name");
            $balance = $getbal->execute(['startdate'=>$oldest,'enddate'=>$newest]);
                
		
            return $balance;
	} 
 
}
?>
