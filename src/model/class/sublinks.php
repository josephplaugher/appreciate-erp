<?php

class sublinks {
    
    private static function header($header) {
    return '<p1 class = "header">'.$header.'</p1><br>';
    }
    
    private static function sublink($function, $value) {
    return '<input class = "button" type = "button" onclick = "'.$function.'" => "'.$value.'"> <br>';
    }
    
    private function accounting() {
        $output = self::header('Accounts and the General Ledger');
        $submenu = ["report('gl')"=>"General Ledger",
            "report('journ')"=>"General Journal",
            "de('journal_entry')"=>"General Journal Entries",
            "report('coa')"=> "Chart of Accounts",
            "trial()" => "Trial Balance", 
            "de('add_account')" => "Add a New Account"];
        foreach($submenu as $func => $label) {
        $output .= self::sublink($func, $label);
        }
        
        $output = self::header('Accounts Receivable');
        $submenu = ["de('create_invoice')" => "Create Invoice",
            "report('inv')" => "View Invoices",
            "de('create_credit')" => "Create Credit",
            "de('receive_payment')" => "Receive Payment",
            "araging()" => "Accounts Receivable, Aging"]; 
        foreach($submenu as $func => $label) {
        $output .= self::sublink($func, $label);
        }
        
        $output = self::header('Accounts Payable');
        $submenu = ["de('enter_supplier_invoice')" => "Enter Supplier Invoice",
            "report('bills')" => "Pay a Bill",
            "de('create_credit')" => "Create Credit",
            "apaging()" => "Accounts Payable, Aging"]; 
        foreach($submenu as $func => $label) {
        $output .= self::sublink($func, $label);
        }
        
        $output = self::header('Statements');
        $submenu = ["stmt('income')" => "Income Statement",
            "balancesheet()" => "Balance Sheet",
            "cashflow()" => "Statement of Cash Flows"]; 
        foreach($submenu as $func => $label) {
        $output .= self::sublink($func, $label);
        }
        $output .= '</div>';
    }
 
    

}
