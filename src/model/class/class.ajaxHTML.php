<?php

require_once 'class.base.php';

class ajaxHTML extends base {

     public $testdata;
    
    public function getTestData(){
        return $this->testdata;
    }
    public function getInvoices($input, $dbh) {
        extract($input);
        try{
        $inv = $dbh->prepare("SELECT invnum, SUM(line_total) AS total FROM invoices WHERE customerid = :id GROUP BY invnum");
        $inv->execute(['id'=>$id]);
        $this->testdata = $result = $inv->fetchall(PDO::FETCH_ASSOC);
        }catch(PDOException $pdoe){
            $this->setError('Query failed: '.$pdoe->getMessage());
        }
        $output = $this->buildInvoiceList($result);
        $this->output = $output;      
        
     }
     
     private function buildInvoiceList($result) {
         if(is_null($result)) {$output = 'No Invoices Found';
           }else{
                $output = '<select name="invoices[]" id="invoices" class="multipleSelect" multiple><option value = "dna">Apply to invoice later</option>';
                foreach($result as $inv) {
                     $output .= '<option value = "'.$inv['invnum'].":".$inv['total'].'" >Invoice #: '.$inv['invnum'].' Total Due: '.$inv['total'].'</option>';
                 }
                 $output .= "</select>";    
            }
        return $output;
    }
    
    public function acctModifier($input) {
        extract($input);
        switch ($type) {
	
            default:
                    break;
            case "Fixed Asset":
                    $return =  "<div class = 'label'><label><p1>Contra Account</p1></label></div>";
                    $return .=  "<div class = 'field'><input name = 'cor_acctname' class = 'textinput' value ='Accumulated Depreciation-".$nametoappend."'></div>";
                    $return .=  "<div class = 'label'><label><p1>Contra Account Number</p1></label></div>";
                    $return .=  "<div class = 'field'><input name = 'cor_acctno' class = 'textinput' value =''></div>";
                    break;

            case "Intangible Asset":
                    $return =  "<div class = 'label'><label><p1>Related Contra Account</p1></label></div>";
                    $return .=  "<div class = 'field'><input name = 'cor' class = 'textinput' value ='Accumulated Ammortization-".$nametoappend."'></div";
                    $return .=  "<div class = 'field'><input name = 'cor_type' class = 'textinput' value ='Accumulated Ammortization-".$nametoappend."' ></div>";
                    $return .=  "<div class = 'label'><label><p1>Contra Account Number</p1></label></div><br>";
                    $return .=  "<div class = 'field'><input name= 'cor_acctno'></div>";
                    break;

            case "Subsidiary":
                    $return =  "<div class = 'label'><label><p1>Parent Account</p1></label></div>";
                    $return .=  "<div class = 'field'><input id = 'cor_acctname' name = 'cor_acctname' class = 'textinput'>";
                    $return .=  "<p id = 'cor_opt' class = 'options'></p></div>";
                    $return .=  "<div class = 'label'><label><p1>Parent Account Number</p1></label></div>";
                    $return .=  "<div class = 'field'><input id = 'cor_acctno' name = 'cor_acctno' class = 'textinput'>";
                    $return .=  "<p id = 'cor_acctno_opt' class = 'options'></p></div>";
                    break;

            case "Expense":
                    $return =  "<div class = 'label'><p1>Subtype</p1></div>";
                    $return .=  "<div class = 'field'><select name = 'subtype' class = 'select'>
                            <option value = ''>N/A</option></option>
                            <option value = 'depreciation'>Depreciation</option></option>
                            <option value = 'ammortization'>Ammortization</option></option>
                            <option value = 'depletion'>Depletion</option></option>
                            </select><br></div>";
                    break;
            }
        $this->output =  $return;
    }
    
}

?>
