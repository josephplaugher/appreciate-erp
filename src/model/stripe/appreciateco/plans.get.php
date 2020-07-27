<?php
\Stripe\Stripe::setApiKey($SECRET_KEY);
$plans = \Stripe\Plan::all(array("limit" => 10));

$i = 0;
foreach($plans->data as $plans){
$plannames[]= $plans->name;
$planprices[]= number_format(($plans->amount/100),2);
$i = $i + 1;
}
?>	
