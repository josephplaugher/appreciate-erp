<!DOCTYPE HTML PUBLIC "//EN">
<body>
<div id = "general" class = "submenu">
<p1 class = "header">Accounts and the General Ledger </p1><br>
<input class = "button" type = "button" onclick = "report('gl')" value = "General Ledger"> <br>
<input class = "button" type = "button" onclick = "report('journ')" value = "General Journal"> <br>
<input class = "button" type = "button" onclick = "ui('journal_entry')" value = "General Journal Entries"> <br>
<input class = "button" type = "button" onclick = "report('coa')" value = "Chart of Accounts"> <br>
<input class = "button" type = "button" onclick = "ui('trialBal')" value = "Trial Balance"> <br>
<input class = "button" type = "button" onclick = "ui('add_account')" value = "Add a New Account"> <br>
</div>

<div id = "ar" class = "submenu">
<p1 class = "header">Accounts Receivable </p1> <br>
<input class = "button" type = "button" onclick="ui('create_invoice')" value = "Create Invoice"> <br>
<input class = "button" type = "button" onclick = "report('invoices')" value = "View Invoices and Credits"> <br>
<input class = "button" type = "button" onclick="ui('create_credit')" value = "Create Credit"> <br>
<input class = "button" type = "button" onclick="ui('receive_payment')" value = "Receive Payment"> <br>
<input class = "button" type = "button" onclick = "araging()" value = "Accounts Receivable, Aging"> <br>
</div>

<div id = "ap" class = "submenu"> 
<p1 class = "header">Accounts Payable </p1><br>
<input class = "button" type = "button" onclick="ui('enter_supplier_invoice')" value = "Enter Supplier Invoice"> <br>
<input class = "button" type = "button" onclick="report('bills')" value = "View Supplier Invoices"> <br>
<input class = "button" type = "button" onclick = "apaging()" value = "Accounts Payable, Aging"> <br> 
</div>


<div id = "statements" class = "submenu">
<p1 class = "header">Statements</p1><br>
<input class = "button" type = "button" onclick="stmts('income_statement')" value = "Income Statement"> <br>
<input class = "button" type = "button" onclick="stmts('balance_sheet')" value = "Balance Sheet"> <br>
<input class = "button" type = "button" onclick="stmts('statement_of_cash_flows')" value = "Statement of Cash Flows"> <br>
</div>

</body>
</html>
