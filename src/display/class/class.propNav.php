<?php

require 'class.formelements.php';

class propNav extends formelements {
    
    public function getView() {
        $output = json_encode($this->view);
        print $output;
    }
    
    
}

?>