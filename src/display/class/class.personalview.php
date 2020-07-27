<?php

require_once 'class.baseview.php';

class personalview extends baseview {
    
public $empid;

public function personal_info() {   
        $output = $this->beginForm('Personal Information','class=adduser');
        $output .= $this->input('Employee ID','','empid','readonly');
        $output .= $this->input('Last Name','','lname','readonly');
        $output .= $this->input('First Name','','fname','readonly');
        $output .= $this->input('Position','','position','readonly');
        $output .= $this->input('Street','','street','readonly');
        $output .= $this->input('City','','city','readonly');
        $output .= $this->input('State','','state','readonly');
        $output .= $this->input('Zip','','zip','readonly');
        $output .= $this->input('Email','','email','readonly');
        $output .= $this->input('Salary','','salary','readonly');
        $output .= $this->input('Startdate',$this->today,'startdate','readonly');
        $output .= $this->openDiv('buttondiv',NULL);
        $output .= $this->button('ajaxsubmit','Edit',NULL);
        $output .= $this->button('close','Close',NULL);
        $output .= $this->closeDiv();
        $output .= $this->closeForm();
        $this->view = $output;
    }
}