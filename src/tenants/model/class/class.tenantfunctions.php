<?php
require '../display/class/class.formelements.php';

class tenantfunctions extends formelements{
     
 public function payRent($dbh) {   
        $rent = $this->rentDetails($dbh);
        $output = $this->beginForm('Pay Rent','class=tenantuser&method=createAccount');
        $output .= $this->input('Month','','month','');
        $output .= $this->input('Year','','year','');
        $output .= $this->input('Amount','','amount','');
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Create Account',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
    
    private function rentDetails($dbh) {
        $id = $_SESSION['tenantid'];
        $rent = $dbh->query("SELECT duedate,amount FROM tenants WHERE tenantid = '$id' ")->fetchall(PDO::FETCH_ASSOC);
        return $rent;
    }
}

?>