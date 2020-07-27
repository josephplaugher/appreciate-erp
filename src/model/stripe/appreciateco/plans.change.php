<?php
\Stripe\Stripe::setApiKey($SECRET_KEY);

$subscription = \Stripe\Subscription::retrieve("$subid");//this assumes the subscription id has been set in the page in which this file in required
$subscription->plan = $newplan;
if(!empty($_SESSION['promotion']['id'])) {
$subscription->coupon = $_SESSION['promotion']['id'];
}
$subscription->save();

?>	
