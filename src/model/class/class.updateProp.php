<?php

class updateProp {
    
    public function addProp($input,$dbh) {
        extract($input);
        try{
        $insert = $dbh->prepare("INSERT INTO props (fname, lname, employer, email, dep1, dep2, dep3, dep4, comments) VALUES ('$fname', '$lname', '$employer', '$email', '$dep1', '$dep2', '$dep3', '$dep4', '$comments')");
        $result = $insert->execute(['fname' =>$fname, 'lname' => $lname, 'employer' =>$employer, 'email' =>$email, 'dep1' =>$dep1, 'dep2' => $dep2, 'dep3' =>$dep3, 'dep4' =>$dep4, 'comments' => $comments]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if($result == 1){
            $this->output = ['success'=>'Tenant added successfully'];
        }
    }
    
    public function addTenant($input,$dbh) {
        extract($input);
        try{
        $insert = $dbh->prepare("INSERT INTO tenants (fname, lname, employer, email, dep1, dep2, dep3, dep4, comments) VALUES ('$fname', '$lname', '$employer', '$email', '$dep1', '$dep2', '$dep3', '$dep4', '$comments')");
        $result = $insert->execute(['fname' =>$fname, 'lname' => $lname, 'employer' =>$employer, 'email' =>$email, 'dep1' =>$dep1, 'dep2' => $dep2, 'dep3' =>$dep3, 'dep4' =>$dep4, 'comments' => $comments]);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        if($result == 1){
            $this->output = ['success'=>'Tenant added successfully'];
        }
    }
}

?>