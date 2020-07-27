<?php
require 'class.base.php';

class subscribe extends base{
    
    public function subscribe($input) {
	// Need a payment token:
	if(!isset($input['stripeToken'])) {
        $this->setError('The order cannot be processed. Please make sure you have JavaScript enabled and try again.');
            }elseif(is_null($this->getErrors())){
            $customer = $this->createCustomer($input);
            if ($customer->id) {
                $this->NewUserDBEntries($input,$customer);
            }
        }
    }
    
    private function createCustomer($input) {
        try {
            // Include the Stripe library:
            require('../stripe/init.php');
            \Stripe\Stripe::setApiKey($SECRET_KEY);
            //create the customer within stripe
            $customer = \Stripe\Customer::create(array(
                "description" => $_SESSION['company_name'],
                "email" => $_SESSION['login_email'],
                "source" => $token,
                "plan" => $_SESSION['plan'],
                "coupon" => $_SESSION['promo']
                )
            );
        }catch(\Stripe\Error\Card $e) {
        // Card was declined.
        $e_json = $e->getJsonBody();
        $err = $e_json['error'];
        $this->setError($err['message']);
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network problem, perhaps try again.
        } catch (\Stripe\Error\InvalidRequest $e) {
            // You screwed up in your programming. Shouldn't happen!
        } catch (\Stripe\Error\Api $e) {
            // Stripe's servers are down!
        } catch (\Stripe\Error\Base $e) {
            // Something else that's not the customer's fault.
        }
        $_SESSION['customer_id'] = $customer->id;
        return $customer;
    }
		
			$_SESSION['customer_id'] = $customer->id;
			// Check that the customer was created.
			if ($_SESSION['customer_id']) {
			//create the new users database and update their status in login
			require('newuserdatabase.php');
			//send the new customer an email

			$sendtoname = $_SESSION['fname'];
			$sendtoemail = $_SESSION['login_email'];
			$body = "<p>$sendtoname, <br><br>Your account is ready to use. If you haven't logged in already you can do so by <a href='https://appreciateco.com/signin.php'>clicking here</a>.<br><br>Account Management<br>Appreciate Corporation</p>";
			include('../email/newuser.email.php');
			header('location: ../signin.php');
			} else { // set an error
		
		$GLOBALS['Error'][] = "something went wrong. Please try again.";
			}

		
} 
}
?>
