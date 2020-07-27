<?php

require 'class.finstatements.php';

class balancesheet extends finstatements {
    
public function getStatement($input,$dbh) {
     
        $headers = $this->setHeader($input,'Balance Sheet');
        foreach($headers as $headers) {
         $this->csv[] = $headers;
         }
        $accounttypes = ['curassets'=>'Current Asset','fixedasset'=>'Fixed Asset','currentliab'=>'Current Liability', 'ltliab'=>'Long-Term Liability','equity'=>'Equity'];
        $accountnames = $this->getAccountNames($dbh,$accounttypes);
        $csv[] = ['Assets'];
        $this->getCategoryBalances('curassets','Current Asset',$accountnames['curasset'],$input,$dbh,'debitbal');
        $this->getCategoryBalances('fixedassets','Fixed Asset',$accountnames['fixedasset'],$input,$dbh,'debitbal');
        $csv[] = ['Total Assets','',array_sum($this->curasset) + array_sum($this->fixedasset)];
        $csv[] = ['',''];
        
        $csv[] = ['Liabilities'];
        $this->getCategoryBalances('currentliab','Current Liability',$accountnames['curliab'],$input,$dbh,'creditbal');
        $this->getCategoryBalances('ltliab','Long-Term Liability',$accountnames['ltliab'],$input,$dbh,'creditbal');
        $csv[] = ['Total Liabilities','',array_sum($this->curliab) + array_sum($this->ltliab)];
        $csv[] = ['',''];
        
        $csv[] = ['Equity'];
        $this->getCategoryBalances('equity','Equity',$accountnames['equity'],$input,$dbh,'creditbal');
        $csv[] = ['',''];
        $csv[] = ['Total Liabilities and Equity','',$this->liabEquity($this->curliab,$this->ltliab,$this->equity)];
        
        $_SESSION['csvstring'] = json_encode($csv);
    $this->output = ['stmt'=>$csv];
    
    }
    
      public function liabEquity($curliab,$ltliab,$equity) {
        if($liabTotal !== 0.00){
            $le = $curliab + $ltliab + $equity;
        }else{
            $le = $equity;
        }
        return $le;
    }
    
}  
?>
