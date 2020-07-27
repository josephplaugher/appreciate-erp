<?php
require_once 'class.base.php';

class adduser extends base{
    
    public $output;
    public $error;
    public $userexistsalready;
    
    public function __construct($input, $dbh, $useraccountobject) {
        $email = $input['email'];
        $duplicate = $this->checkDuplicate($email,$dbh);
        if($duplicate == false){
            $input['id'] = $this->generateId('user_id', $dbh);
            $status1 = $this->userEntry($input,$dbh);
            $status2 = $this->loginEntry($input,$useraccountobject);
            $this->sendUserEmail($input);  
        }
        if(!empty($this->error)) {
            $output = ['error'=>$this->error];
        }else{
            $output = ['success'=>'New user added successfully! A random password has been generated and emailed to the new user. Please have them change their password immediately.'];
        }        
    }
    
    private function checkDuplicate($email, $dbh) {//will move this function into the new user class when I get around to that
        //check the email for duplicates
        $emailcheck = $dbh->query("SELECT email from users WHERE email = '$email' ")->fetch(PDO::FETCH_ASSOC);
        if($emailcheck['email']) {
           $this->output = ['error'=>'That email address is already in use on your account.'];
           }else{  
           return false;
        }
    }
 
    public function userEntry($input,$dbh) {
        extract($input);
        try{
        $update = $dbh->prepare("INSERT INTO users (lname, fname, position, street, city, state, zip, email, salary, startdate, id) VALUES "
                . "(:lname, :fname, :position, :street, :city, :state, :zip, :email, :salary, :startdate, :empid)");
        $result1 = $update->execute($input);
        if(!$result1) {throw new Exception('Users datatable entry failed');}

        //update access table within user database
        $access = $dbh->prepare("INSERT INTO access (lname, fname, email, empid) VALUES (:lname, :fname, :email, :empid)");
        $result2 = $access->execute(['lname' =>$lname, 'fname'=>$fname, 'email'=>$email, 'empid'=>$empid]);
        if(!$result2) {throw new Exception('Access datatable entry failed');}
        }catch(PDOException $pdoe){
            $error = $pdoe->getMessage();
        }
        if($error){
            $this->error = $error;
        }
    }
    
    public function loginEntry($input,$ua) {
        extract($input);
        try{
        //update users entry in system table login.	
        $coname = $_SESSION['company_name'];
        $logindsn = "pgsql:host=localhost;port=5432;dbname='liquidphase';user=postgres;password=skippy1985";

        // create a PostgreSQL database connection
        $conn = new PDO($logindsn);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if(!$conn) {throw new Exception('Could not connect to login database');}

        $password = $ua->genpassword();
        if(!$password) {throw new Exception('Failed to generate random password');}

        $newuser = $conn->prepare("INSERT INTO login (email, lname, fname, company_name, password, empid, status) VALUES "
                                                . "(:email, :lname, :fname, :company_name, :password, :empid, 'addeduser')");
        $result3 = $newuser->execute(['email'=>$email, 'lname' =>$lname, 'fname'=>$fname, 'company_name'=> $coname, 'password'=>$password,'empid'=>$empid]);
        if(!$result3) {throw new Exception('Login datatable entry failed');}
        }catch(PDOException $pdoe){
            $error = $pdoe->getMessage();
        }
        if($error){
            $this->error = $error;
        }
    }
    
    public function sendUserEmail($input) {    
        
        require_once '../swiftmailer-5.x/lib/swift_required.php';
        date_default_timezone_set('America/Los_Angeles');//required by swiftmailer
        // Create the Transport
        try{
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "SSL")->setUsername('joseph@appreciateco.com')->setPassword('apache1985');
        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        // Create a message
        $body = "<p>Hello $fname,<br><br>Welcome to Appreciate accounting and business management software! It won't take long to finish creating your account."
                . "<a href='https://appreciateco.com/model/users/adduserverify.php?email=$email'> Click here</a> to verify your email address.<br><br>"
                . "Account Management<br>Appreciate Corporation</p>";

        $message = Swift_Message::newInstance('Welcome to Appreciate!')->setFrom(array('accounts@appreciateco.com' => 'Appreciate Corporation'))->setBody($body, "text/html");
        $message->setTo([$email => $fname]);
        $numSent = $mailer->send($message);
            if(!($numSent > 0)) {Throw new Exception('The new user database entry failed');}
        }catch(PDOException $pdoe){
            $error = $pdoe->getMessage();
        }catch(Exception $e){
            $error = $e->getMessage();
        }
        if($error){
            $this->error = $error;
        }
    }
    
    
    
}


?>