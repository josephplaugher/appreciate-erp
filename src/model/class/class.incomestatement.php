<?php

require 'class.finstatements.php';

class incomestatement extends finstatements {
    
    public function getStatement($input,$dbh) {
     
        $headers = $this->setHeader($input,'Income Statement');
        foreach($headers as $headers) {
         $this->csv[] = $headers;
         }
        $accounttypes = ['rev'=>'Income', 'cogs'=>'Cost of Goods sold','exp'=>'Expense'];
        $accountnames = $this->getAccountNames($dbh,$accounttypes);
        
        $this->getCategoryBalances('income','Income',$accountnames['rev'],$input,$dbh,'creditbal');
        foreach($accountnames as $cogs){$checkcogs[] = $this->getBalance($dbh,$input,$cogs,'debitval');}
        if(array_sum($checkcogs) == 0.00){
            $this->getCategoryBalances('cogs','Cost of Goods Sold',$accountnames['cogs'],$input,$dbh,'debitbal');
        }
        $this->getCategoryBalances('exp','expense',$accountnames['exp'],$input,$dbh,'debitbal');
    
        $this->csv[] = ['Net Income','',$this->netIncome($this->income,$this->cogs,$this->exp)];
        
        $_SESSION['csvstring'] = json_encode($this->csv);
        $this->output = ['stmt'=>$this->csv];
    
    }
   
    private function netIncome($incomeTotal,$cogsTotal,$expTotal) {
        if($cogsTotal !== 0.00){
            $ni = $incomeTotal - $cogsTotal - $expTotal;
        }else{
            $ni = $incomeTotal - $expTotal;
        }
        return $ni;
    }
}
?>