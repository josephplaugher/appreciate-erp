<?php
	// Need a payment token:
	if (isset($_POST['stripeToken'])) {

		$token = $_POST['stripeToken'];

		// Check for a duplicate submission, just in case:
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$GLOBALS['Error'][] = 'You have apparently resubmitted the form. Please do not do that because you might be charged twice.';
		} else { // New submission.
			$_SESSION['token'] = $token;
		}

	} else {
		$GLOBALS['Error']['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
	}
	// If no errors, process the order:
$errorcount = count($GLOBALS['Error']);
	if(($errorcount) == 0) { 
		// create the charge on Stripe's servers - this will charge the user's card
		try {
			
			\Stripe\Stripe::setApiKey($SECRET_KEY);
//this assumes there is already a customer object saved as $account
$account->source = $token;
$account->save();

$success = "Your card details have been updated!";

		} catch (\Stripe\Error\Card $e) {
		    // Card was declined.
			$e_json = $e->getJsonBody();
			$err = $e_json['error'];
			$GLOBALS['Error']['stripe'] = $err['message'];
		} catch (\Stripe\Error\ApiConnection $e) {
		    // Network problem, perhaps try again.
		} catch (\Stripe\Error\InvalidRequest $e) {
		    // You screwed up in your programming. Shouldn't happen!
		} catch (\Stripe\Error\Api $e) {
		    // Stripe's servers are down!
		} catch (\Stripe\Error\Base $e) {
		    // Something else that's not the customer's fault.
		}
} 
?>
