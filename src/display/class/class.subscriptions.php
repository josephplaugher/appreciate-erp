<?php
require_once('vendor/autoload.php');
class subscription {

function enterprise(){
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey("sk_test_KNqi2o3ZmvtmHnTo1XQ2p4bw");

$plan = \Stripe\Plan::create(array(
  "name" => "Enterprise",
  "id" => "enterprise",
  "interval" => "month",
  "currency" => "usd",
  "amount" => 180,
));
}

}

?>
