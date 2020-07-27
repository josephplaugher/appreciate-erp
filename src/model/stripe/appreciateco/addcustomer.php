<?php

	// create the customer in Stripe's servers
		try {

			// Include the Stripe library:
		require('stripe/init.php');
			
			\Stripe\Stripe::setApiKey('sk_test_KNqi2o3ZmvtmHnTo1XQ2p4bw');

			// Charge the order:
			// add something unique to id the customer with a "deacription" field
			$customer = \Stripe\Customer::create(array(
				"description" => $customerid,
				"email" => $email
				)
			);
		
			$customer_id = $customer->id;
			// Check that the customer was created.
			if ($customer_id) {
				$_SESSION['customerid'];
				//move on to plan selection page
				//header('location: companyinfo.php');
			
			} else { // Customer was not created!
				print "stripe didn't work";
				$GLOBALS['Error'][] = '<h4>System Error</h4><p>This error has been sent to our administrators. You may try again or come back later</p>';
			}
		//***need to verify this error stuff***
		} catch (\Stripe\Error\Customer $e) {
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
		}

?>
