<?php

require 'class.statements.php';

class cashflow extends statements {
    
public function getStatement($input,$dbh) {
     
        $headers = $this->setHeader($input,'Balance Sheet');
        foreach($headers as $headers) {
         $csv[] = $headers;
         }
        $accounttypes = ['currentassets'=>'Current Asset','fixedasset'=>'Fixed Asset','currentliab'=>'Current Liability', 'ltliab'=>'Long-Term Liability','equity'=>'Equity'];
        $accountnames = $this->getAccountNames($dbh,$accounttypes);
        $csv[] = ['Assets'];
        $csv[] = ['','Current Assets'];
        foreach($accountnames['currentassets'] as $asset){
            $bal = $this->getBalaceSheetBalance($dbh,$input,$asset,'currentasset');
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $currentassetTotal[] = $bal;
                $csv[] = ['','',$asset,$this->numberFormat($bal)];
            }
        }
        $csv[] = ['','Total Current Assets','',array_sum($currentassetTotal)];
        $csv[] = ['','Fixed Assets'];
        foreach($accountnames['fixedassets'] as $asset){
            $bal = $this->getBalaceSheetBalance($dbh,$input,$asset,'fixedasset');
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $fixedassetTotal[] = $bal;
                $csv[] = ['','',$asset,$this->numberFormat($bal)];
            }
        }
        $csv[] = ['','Total Fixed Assets','',array_sum($fixedassetTotal)];
        $csv[] = ['Total Assets','',array_sum($currentassetTotal) + array_sum($fixedassetTotal)];
        
        $csv[] = ['',''];
        
        $csv[] = ['Liabilities'];
        $csv[] = ['Current Liabilities'];
        foreach($accountnames['currentliab'] as $liab){
            $bal = $this->getBalaceSheetBalance($dbh,$input,$liab,'currentliab');
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $liabTotal[] = $bal;
                $csv[] = ['','',$liab,$this->numberFormat($bal)];
            }
        }
        $csv[] = ['','Total Current Liabilities','',array_sum($liabTotal)];
        
         $csv[] = ['Long-Term Liabilities'];
        foreach($accountnames['ltliab'] as $liab){
            $bal = $this->getBalaceSheetBalance($dbh,$input,$liab,'ltliab');
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $ltliabTotal[] = $bal;
                $csv[] = ['','',$liab,$this->numberFormat($bal)];
            }
        }
        $csv[] = ['','Total Long-Term Liabilities','',array_sum($ltliabTotal)];
        $csv[] = ['Total Liabilities','',array_sum($currentliabTotal) + array_sum($ltliabTotal)];
        $csv[] = ['',''];
        
        $csv[] = ['Equity'];
        foreach($accountnames['equity'] as $equity){
            $bal = $this->getBalaceSheetBalance($dbh,$input,$equity,'equity');
            if($input['zero'] == 'no' && $bal == 0.00){ }else{
                $equityTotal[] = $bal;
                $csv[] = ['',$equity,$this->numberFormat($bal)];
            }
        }
        $csv[] = ['Total Equity','', array_sum($equityTotal)];
        $csv[] = ['',''];
        $csv[] = ['Total Liabilities and Equity','',$this->liabEquity($liabTotal,$equityTotal)];
        
        $_SESSION['csvstring'] = json_encode($csv);
    $this->output = ['stmt'=>$csv];
    
    }
}  
?>