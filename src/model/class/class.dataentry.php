<?php
require_once 'class.base.php';

class dataentry extends base {
    
    public function add_customer($input,$dbh) {
        unset($input['empid']);
        try{
        $input['id'] = $this->generateId('cust_id', $dbh);
        $insert = $dbh->prepare("INSERT INTO customers (name, industry, contact, phone, email, street, city, state, zip, id) "
                . "VALUES (:name, :industry, :contact, :phone, :email, :street, :city, :state, :zip, :id)");
        $result = $insert->execute($input);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if($result){ 
        $this->output = ['success' => 'Customer added successfully'];
        }
    }
    
    public function add_supplier($input,$dbh) {
        unset($input['empid']);
        try{
        $input['id'] = $this->generateId('supplier_id', $dbh);
        $insert = $dbh->prepare("INSERT INTO suppliers (name, industry, contact, phone, email, street, city, state, zip, id) "
                . "VALUES (:name, :industry, :contact, :phone, :email, :street, :city, :state, :zip, :id)");
        $result = $insert->execute($input);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if($result){ $this->output = ['success' => 'Supplier added successfully'];}
    }
    
    public function update_supplier($input,$dbh) {
        unset($input['empid']);
        try{
        $insert = $dbh->prepare("UPDATE suppliers SET (name, industry, contact, phone, email, street, city, state, zip) "
                . "= (:name, :industry, :contact, :phone, :email, :street, :city, :state, :zip) WHERE id = :id");
        $result = $insert->execute($input);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if($result){ $this->output = ['success' => 'Supplier updated successfully'];}
    }
    
    public function update_customer($input,$dbh) {
        unset($input['empid']);
        try{
        $insert = $dbh->prepare("UPDATE customers SET (name, industry, contact, phone, email, street, city, state, zip) "
                . "= (:name, :industry, :contact, :phone, :email, :street, :city, :state, :zip) WHERE id = :id");
        $result = $insert->execute($input);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if($result){ $this->output = ['success' => 'Customer updated successfully'];}
    }
    
    public function add_tenant($input,$dbh) {
        unset($input['empid']);
        try{
        $input['id'] = $this->generateId('tenant_id', $dbh);
        $insert = $dbh->prepare("INSERT INTO tenants (fname, lname, employer, email, dep1, dep2, dep3, dep4, comments, id, status) "
                . "VALUES (:fname, :lname, :employer, :email, :dep1, :dep2, :dep3, :dep4, :comments, :id, 'new' ");
        $result = $insert->execute($input);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        } 
        if($result){ $this->output = ['success' => 'Tenant added successfully'];}
    }
}

?>