<?php

class accounts {
	
	public $dbh;
	
	public function getAccounts($dbh) {
            $this->dbh = $dbh;
            $acct = $dbh->query("SELECT acctname, acctno FROM sys_coa ORDER BY acctno ASC, acctname ASC")->fetchall(PDO::FETCH_ASSOC);
            return $acct;
        }

        public function getRevAccounts($dbh) {
            $this->dbh = $dbh;
            $acct = $dbh->query("SELECT acctname, acctno FROM sys_coa WHERE type = 'Income' ORDER BY acctno ASC, acctname ASC")->fetchall(PDO::FETCH_ASSOC);
            return $acct;
        }
	
        public function getBanks($dbh) {
            $this->dbh = $dbh;
            $acct = $dbh->query("SELECT acctname, acctno FROM sys_coa WHERE type = 'Bank' ORDER BY acctno ASC, acctname ASC")->fetchall(PDO::FETCH_ASSOC);
            return $acct;
	}
       
}
?>
