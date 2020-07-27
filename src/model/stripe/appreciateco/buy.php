<?php
	// Need a payment token:
	if (isset($_POST['stripeToken'])) {

		$token = $_POST['stripeToken'];

		// Check for a duplicate submission, just in case:
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$GLOBALS['Errors'][] = 'You have apparently resubmitted the form. Please do not do that.';
		} else { // New submission.
			$_SESSION['token'] = $token;
		}

	} else {
		$GLOBALS['Errors']['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
	}

	// Set the order amount somehow:
	$amount = 8000; // $20, in cents

	// Validate other form data!

	// If no errors, process the order:
$errorcount = count($GLOBALS['Error']);
	if(($errorcount) == 0) { 
		// create the charge on Stripe's servers - this will charge the user's card
		
			// Include the Stripe library:
		require('stripe/init.php');
			
			\Stripe\Stripe::setApiKey('sk_test_KNqi2o3ZmvtmHnTo1XQ2p4bw');

//create the customer within stripe
			$customer = \Stripe\Customer::create(array(
				"description" => $_SESSION['customerid'],
				"email" => $_SESSION['login_email']
				)
			);
		
			$customer_id = $customer->id;
			// Check that the customer was created.
			if ($customer_id) {
				$_SESSION['customerid'];
					// set the subscription:
			$subscribe = \Stripe\Subscription::create(array(
 				 "customer" => $_SESSION['customerid'],
 				 "plan" => $_SESSION['plan'] //this needs to be variable set by the customer
				)
			);
			/* Charge the order:
			// add something unique to id the customer with a "deacription" field
			$charge = \Stripe\Charge::create(array(
				"amount" => $amount, // amount in cents
				"currency" => "usd",
				"source" => $token,
				"description" => "test by test@email.com"
				)
			);

			// Check that it was paid:
			if ($charge->paid == true) {

				// Store the order in the database.
				// Send the email.
				// Celebrate!

			} else { // Charge was not paid!
				$GLOBALS['Error'][] = '<h4>Payment System Error!</h4>Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.';
			}

		} catch (\Stripe\Error\Card $e) {
		    // Card was declined.
			$e_json = $e->getJsonBody();
			$err = $e_json['error'];
			$errors['stripe'] = $err['message'];
		} catch (\Stripe\Error\ApiConnection $e) {
		    // Network problem, perhaps try again.
		} catch (\Stripe\Error\InvalidRequest $e) {
		    // You screwed up in your programming. Shouldn't happen!
		} catch (\Stripe\Error\Api $e) {
		    // Stripe's servers are down!
		} catch (\Stripe\Error\Base $e) {
		    // Something else that's not the customer's fault.
		}*/
                        } 
        }
?>
