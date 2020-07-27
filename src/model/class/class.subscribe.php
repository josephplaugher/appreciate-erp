<?php
require_once 'class.base.php';

class subscribe extends newuser{
    
    public $testval;
    
    public function getTest(){
        return $this->testval;
    }
    
    public function processToken($input) {
        $dsn = $this->newUserDSN();   
	// Need a payment token:
	if(!isset($input['stripeToken'])) {
        $this->setError('The order cannot be processed. Please make sure you have JavaScript enabled and try again.');
            }elseif(is_null($this->getErrors())){
            $customer = $this->createCustomer($input['stripeToken']);
            if ($customer->id) {
                $companyID = $this->generateId('company_id_seq', $dsn);
                $this->updateNewUserLogin($companyID,$customer,$dsn);
                $this->newUserDB($companyID,$dsn);
                $this->accountCompleteEmail($input);
                $_SESSION['login_user'] = $_SESSION['loginemail'];
            }
            $this->output = ['goto'=>'ui.php?class=home&method=main'];
        }
    }
    
    private function createCustomer($token) {
        try {
            // Include the Stripe library:
            require 'stripe/init.php';
            require 'stripe/appreciateco/keys.php';
            \Stripe\Stripe::setApiKey($SECRET_KEY);
            //create the customer within stripe
            $this->testval = $customer = \Stripe\Customer::create(array(
                "description" => $_SESSION['company_name'],
                "email" => $_SESSION['loginemail'],
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
            $this->setError('apiconnection error: '.$e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->setError('invalid request error: '.$e->getMessage());
        } catch (\Stripe\Error\Api $e) {
            $this->setError('api error: '.$e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            $this->setError('some other error: '.$e->getMessage());
        }
        $_SESSION['customer_id'] = $customer->id;
        return $customer;
    }
    
    private function updateNewUserLogin($companyID,$customer,$dsn) {
        $email = $_SESSION['loginemail'];
        try{
        $updateID = $dsn->prepare("UPDATE login SET (company_id, customerid, status) = (:company_id, :customerid, 'current') WHERE email = :email");
        $updateID->execute(['company_id'=>$companyID, 'customerid'=>$customer->id, 'email'=>$email]);
        }catch (PDOException $pdoe) {
            $this->setError('update login info failed: '.$pdoe->getMessage());
        }
    }
    
    private function newUserDB($companyID,$dsn) {
        extract($_SESSION);
        // Create the database for the new customer, named after the company, inheriting everything from sample_co.
        try{
        $newacct = $dsn->query('CREATE DATABASE "'.$companyID.'" TEMPLATE newusertemplate');

        $dbh = 'pgsql:host=localhost;port=5432;dbname='.$companyID.';user=postgres;password=skippy1985';

        //connect to new user database and add them as a user
        $newuserconn = new PDO($dbh);
        $newuserconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $empid = $this->generateId('emp_id', $newuserconn);
        $emp = $newuserconn->prepare("INSERT INTO users (lname, fname, email, id) VALUES (:lname, :fname, :email, :empid)");
        $emp->execute(['lname'=>$lname, 'fname'=>$fname, 'email'=>$email,'empid'=>$empid]);
        
        //add the user to the access table
        $access = $newuserconn->prepare("INSERT INTO access 
        (lname, fname, empid, email, administrator, withdrawals, deposits, reconcile_bank, undo_bank_rec, general_ledger, journal_entries, edit_coa, ar, ap, fin_stmts) values 
        (:lname, :fname, :empid, :email, 1, 1, 1, 1, 1 ,1 ,1 ,1 ,1 ,1 ,1)");
        $access->execute(['lname'=>$lname, 'fname'=>$fname, 'empid'=>$empid, 'email'=>$email]);
        }catch (PDOException $pdoe) {
            $this->setError('one or more new user DB queries failed: '.$pdoe->getMessage());
        }
    }
		
    private function accountCompleteEmail() {    
        require 'swiftmailer-5.x/lib/swift_required.php';
        $swiftTransport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "SSL");
        // Create the Mailer using the Transport
        $swiftMailer = Swift_Mailer::newInstance($swiftTransport);
        $swiftMessage = Swift_Message::newInstance('Welcome to Appreciate!');
        extract($_SESSION);
        date_default_timezone_set('America/Los_Angeles');//required by swiftmailer
        // Create the Transport
        try{
        $swiftTransport->setUsername('joseph@appreciateco.com')->setPassword('apache1985');
        // Create the Mailer using your created Transport
        // Create a message
        $body = "<p>Hello $fname,<br><br>Your account is ready to use. If you haven't logged in already you can do so by "
                . "<a href='https://appreciateco.com/display/ui.php?class=userview&method=login.php'>clicking here</a>.<br><br>"
                . "Account Management<br>Appreciate Corporation</p>";

        $swiftMessage->setFrom(array('accounts@appreciateco.com' => 'Appreciate Corporation'))->setBody($body, "text/html");
        $swiftMessage->setTo([$loginemail => $fname]);
        $numSent = $swiftMailer->send($swiftMessage);
            if(!($numSent > 0)) {
                Throw new Exception('The new user database entry failed');
            }else{ $this->setEmailNotif($resend);}
        }catch(PDOException $pdoe){
            $error = $pdoe->getMessage();
        }catch(Exception $e){
            $error = $e->getMessage();
        }
        if($error){
            $this->setError($error);
        }
    }
    
}
?>
