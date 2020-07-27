<?php

require_once 'class.base.php';

class table extends base {

    public function openTable(){
        return "<div class = 'table'>";
    }
 
    public function basicHeader($input) {
        $output = "<div class = 'row'>";
            foreach ($input as $item) {
            $output .= "<div class = 'header'>" . $item . "</div>";
        } 
        $output .= "</div>";//closes the row div
        return $output;
        }
    
    public function basicData($data, $id=NULL,$page ){
        $output = '';
        foreach ($data as $list) {
           $output .= $this->basicRow($list,$id,$page);
        }  
        return $output;
    }

    private function basicRow($data, $id =null,$page) {
        $id = $this->setID($data);
        $output = "<div class = 'row' id = '".$page.":".$id."'>";
        foreach($data as $key => $data) {
            $output .= "<div class = 'data'>$data</div>";
            }
        $output .= "</div>";
        return $output;
    }
  
    private function setID($data) {
       if(isset($data['acctno'])){
       return $data['acctno'];
       }elseif(isset($data['invnum'])){
       return $data['invnum'];
       }elseif(isset($data['creditnum'])){
       return $data['creditnum'];
       }else{
       return $data['id'];
       }
    }
  
    private function setURL($key,$item,$title) {
        switch($key){
        case 'id':
        case 'acctno':
        case 'bankno':
        case 'invnum':
            $output = "<div class = 'data'><a href='../display/ui.php?id=" . $item . "&class=dataview&method=". $title."'>" . $item . "</a></div>";
            break;
        default:
            $output = "<div class = 'data'>" . $item . "</div>";
        }
        return $output;
    }

    public function header($input) {
        if(!is_array($input)) {
        $listitem = array($input);
        }else{$listitem = $input;}    
        $output = "<div class = 'row'>";
            foreach ($listitem as $item) {
            $output .= "<div class = 'header'>" . $item . "</div>";
            } 
        $output .= "</div>";//closes the row div
        return $output;
    }

    public function bankData($data,$currentbal) {
        $balance = $currentbal;
        foreach($data as $row) {
            $output .= $this->bankTableRow($row,$balance);
            $newbal = $this->totalBalChange($balance,$row['debit'],$row['credit']);
            $balance = $newbal;   
            unset($totalchange);
        }
        return $output;
    }
 
    public function bankTableRow($data,$balance) {
         $data['balance'] = $balance;
         $transid = $data['transid']; //sets the transid to go into the clearing function in javascript
         $transamt = $this->setTransAmt($data['debit'],$data['credit']);//sets the amount to place into balance calculator function in javascrip
         if(!is_null($data['clr'])){ $checked = 'checked';} ;
         $output = "<div class = 'row'>";
         foreach($data as $key => $data) {
             $output .= "<div class = 'data'>";
             switch($key) {
                case "clr":
                    $output .= "<input type = 'checkbox' ".$checked." disabled id = 'choice' class = 'transclear' name = 'transinfo' value = '".$transid.":".$transamt."' >";
                    break;
                case "balance":
                case "debit":
                case "credit":
                    $output .= number_format($data,2);
                    break;
                default:
                    $output .= $data; 
                    break;
            } 
            $output .= "</div>";
         }
         $output .= '</div>';
         return $output;
 }
 
    public function recData($data) {
        foreach($data as $row) {
            $output .= $this->recTableRow($row,$balance);
        }
        return $output;
    }
 
    public function recTableRow($data) {
         $transid = $data['transid']; //sets the transid to go into the clearing function in javascript
         $transamt = $this->setTransAmt($data['debit'],$data['credit']);//sets the amount to place into balance calculator function in javascrip
         $output = "<div class = 'row'>";
         unset($data['transid']);
         if(!is_null($data['clr'])){ $checked = 'checked';} ;
         foreach($data as $key => $data) {
             $output .= "<div class = 'data'>";
             switch($key) {
                case "clr":
                    $output .= "<input type = 'checkbox' ".$checked." class = 'transclear' name = 'transinfo' value = '".$transid.":".$transamt."' >";
                    break;
                default:
                    $output .= $data; 
                    break;
            } 
            $output .= "</div>";
         }
         $output .= '</div>';
         return $output;
    }
 
     public function setTransAmt($debit,$credit) {
         if(!is_null($debit)) { 
            return $debit; 
         }elseif(!is_null($credit)) { 
            return -$credit; 
         }
     }

      public function totalBalChange($balance,$debit,$credit) {
         if(!is_null($debit)) { 
             $newbal = ($balance + (-$debit)); 
         }
         if(!is_null($credit)) { 
             $newbal = ($balance + $credit); 
         }
         return $newbal;
     }

     public function getCheckedStatus($data) {
         if(!(is_null($data))) {
             return 'checked';
         }else{
             return NULL;
         }
     }

     public function closeTable(){
        return "</div>";
     }
}
?>