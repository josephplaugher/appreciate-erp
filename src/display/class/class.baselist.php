<?php

class baselist {
   
    public $query;
    public $dbh;
    public $methodname;
    public $title;
    public $headers;
    public $termkey;
    public $formtitle;

    public function setMethod($methodname) {
        $this->methodname = $methodname;
      }
        
    public function getMethodName(){
      return $this->methodname;
      }
          
    public function getQuery(){
      return $this->query;
      }
      
    public function getTitle(){
      return $this->title;
      }
      
    public function getHeaders(){
      return $this->headers;
      }  
      
    public function getTermKey(){
      return $this->termkey;
      }    
}
      
?>