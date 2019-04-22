<?php

abstract class  Person {
    /* Person Attributes */
    protected $name;
    protected $E_mail;
    protected $ID;
    protected $password;
    protected $image;
    public $db_connection;


    /*getter & setter methods*/
    function getName() {
        return $this->name;
    }


    function getE_mail() {
        return $this->E_mail;
    }

    function getID() {
        return $this->ID;
    }

    function getPassword() {
        return $this->password;
    }

    function getImage() {
        return $this->image;
    }

    function setName($name) {

        $filterfname= filter_var($name,FILTER_SANITIZE_STRING);
        if(ctype_space($filterfname))
        {return "fname can't be empty";}
        else
        {
           if(strlen($filterfname)<2 || strlen($filterfname)>15)
           { return 'Name must be between 2-15 character';}
           else
           {
            $this->name = $name;
            return NULL;
           }
        }
    }

    function setE_mail($E_mail) {

        $filtermail= filter_var($E_mail,FILTER_SANITIZE_EMAIL);
        if(!filter_var($filtermail,FILTER_VALIDATE_EMAIL))
        {$signuperror[]='pls enter valid E-mail';}
        else
        {
            $this->E_mail = $E_mail;
            return NULL;
        }
    }

    function setID($ID) {
        $this->ID = $ID;
    }

    function setPassword($password) {

        if(empty($password))
        { return 'password can\'t be empty '; }
        else
        {
          if(strlen($password)<8 || strlen($password)>30)
          {  return 'password must be between (8,30)'; }
          else
          {
              $this->password = $password;
              return NULL;
          }
        }
    }

    function setImage($image) {
        $this->image = $image;
    }

    /* class Methods */

    public static function logout ($pageType="login.php")
    {
        session_unset();  //unset variables of the session [All variables]
        session_destroy(); //Destroy the Session [free session No more will be created]
        $hour = time()-3600*24*30*30; // must be more than 30 day
        setcookie('E_mail', $E_mail, $hour); // fire cookie
        setcookie('pass', $password, $hour); // fire cookie
        header("Location: $pageType");
        exit();
    }
    
    public abstract function viewServices(Services $service,$id);
    public function showSelectedServices (Services $s)
    {

    }
    public function searchForService ($serviceID){

        $Service = new Services();
        $Service->searchForService("SELECT * FROM service WHERE s_id =  ?",
                        $serviceID,$this->db_connection);

    }
    public abstract function RemoveService (Services $service);

    public static function forgetPassword ($E_mail)
    {
        $db_connection = new Database();
        $query = "SELECT * FROM manager WHERE `E-mail` = ? ";
        $Attributes = array($E_mail);
        $result = $db_connection->select($query,$Attributes);
        if($result)
        {
            $data=$result[0];
            require  '../API_library/phpmailer/PHPMailerAutoload.php';
            $to = $E_mail;
            $subject = "remembering with password";
            $body = "Dear ". $data['name'] .", \r\n We sending this mail to remrmber you with your password, \r\n yor password: ".$data['password'];
            //create new object of phpmailer API (web_service)
             $mail = new PHPMailer();
             $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
            );
            //$mail->SMTPDebug = 2;                                  // Enable verbose debug output

             $mail->isSMTP();                                        // Set mailer to use SMTP
             $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
             $mail->SMTPAuth = true;                               // Enable SMTP authentication
             $mail->Username = 'kinghh200@gmail.com';                 // SMTP username
             $mail->Password = 'darkdemon1996';                           // SMTP password
             $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
             $mail->Port = 465;                                    // TCP port to connect to

             $mail->setFrom($mail->Username);
             $mail->addAddress($to);     // Add a recipient
             $mail->addReplyTo($mail->Username);
             $mail->isHTML(true);                                  // Set email format to HTML

             $mail->Subject = $subject;
             $mail->Body    = $body;
            // check email send or not
            if($mail->send())
            {
               return 'Mail_Sent';
            }
            else
            {
               return 'Mail_Not_Sent';
            }
        }
        else
        {
            return 'Mail_Not_Found';
        }
    }

    public abstract function sendMessage (Message $m);
    public abstract function readMessage (Message $m , $sender , $type);
    public abstract function viewSpecificMessage ($sender , $reciever , $date);
    public abstract function changeMessageStatus ($message_id);

}
