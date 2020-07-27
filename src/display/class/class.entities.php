<?php

class entity {
	
	public $dbh;
	
	public function getEntities($dbh) {
            $this->dbh = $dbh;
            $list = $dbh->query("SELECT ein, name FROM entity ORDER BY name DESC")->fetchall(PDO::FETCH_ASSOC);
            return $list;
        }

        public function getProperties($dbh) {
            $this->dbh = $dbh;
            $list = $dbh->query("SELECT acctname, acctno FROM sys_coa WHERE type = 'Income' ORDER BY acctno ASC, acctname ASC")->fetchall(PDO::FETCH_ASSOC);
            return $list;
        }
	
        public function getUnits($dbh) {
            $this->dbh = $dbh;
            $list = $dbh->query("SELECT acctname, acctno FROM sys_coa WHERE type = 'Bank' ORDER BY acctno ASC, acctname ASC")->fetchall(PDO::FETCH_ASSOC);
            return $list;
	}
       
}
?>
