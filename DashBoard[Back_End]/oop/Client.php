<?php

class Client extends Person {
     /* Client Attributes */
   private $gender;
   private $regStatus;
   private $dateOfRegsiter;
   private $DOB;
   private $profile;
   private $iD;
   private $coins;


   /* Class Constructor*/
   function __construct() {
       $this->profile = new Profile();
   }
    public function __wakeup()
    {
        $this->connect();
    }
   /*getter & setter methods*/
   function getID() {
       return $this->iD;
   }

   function setID($iD) {
       $this->iD = $iD;
   }

    function getGender() {
        return $this->gender;
    }
    function getRegStatus() {
        return $this->regStatus;
    }
    function getDateOfRegsiter() {
        return $this->dateOfRegsiter;
    }
    function getDOB() {
        return $this->DOB;
    }
    function getProfile(){
        $this->db_connection = new Database();
        return $this->profile;
    }
    function setGender($gender) {
         $this->gender = $gender;
    }

    function setRegStatus($RegStatus) {
        $this->regStatus = $RegStatus;
    }

    function setDateOfRegsiter($DateOfRegsiter) {
         $this->dateOfRegsiter = $DateOfRegsiter;
    }

    function setDOB($DateOfBirth) {
        $this->DOB = $DateOfBirth;
    }

    function getCoins() {
        return $this->coins;
    }

    function setCoins($coins) {
        $this->coins = $coins;
    }

         /* class Methods */
    public function connect()
    {
        $this->db_connection = new Database();
    }
    public function editInfo()
    {
        $this->connect();
        $sql = "UPDATE `customer` SET `c_name`=? WHERE `c_id`=?";

        $Attibutes=array
        (
            $this->getName(),
            $this->getID()
        );
        return $this->db_connection->update($sql,$Attibutes).'ya';
    }
    public function setNewPassword()
    {
        $this->connect();
        $sql = "UPDATE `customer` SET `password`=? WHERE `c_id`=?";

        $Attibutes=array
        (
            $this->getPassword(),
            $this->getID()
        );
        return $this->db_connection->update($sql,$Attibutes);
    }

    public function uploadImage()
    {

        $this->connect();
        $sql = "UPDATE `customer` SET `image`=? WHERE `c_id`=?";

        $Attibutes=array
        (
            $this->getImage(),
            $this->getID()
        );
        return $this->db_connection->update($sql,$Attibutes);
    }
    function setimgName($imgName)
    {
        $this->image=$imgName;
    }
    
    function setimg($photo_name,$photo_size,$mail , $check=0)
    {
        if($check==0)
        {
            $avilabelExtension = array ("jpeg","jpg","png","gif");
            $image_name_array = explode(".", $photo_name);
            $img_extension = end($image_name_array);
            $get_img_type= strtolower($img_extension);
            if(!in_array($get_img_type, $avilabelExtension))
            {return "the photo you entered is not vaild";}
            else if($photo_size>2097152 || $photo_size==0)
            { return "image can\'t be more than 2 MB"; }
            else
            {
                $imageDBName=$mail.'-'.$photo_name;
                $this->image=$imageDBName;
                return NULL;
            }
        }
        else
        {
           $this->image=$photo_name;
        }
    }

    public function viewInfo()
    {
        $this->connect();
        $sql = "Select * From `customer` WHERE `c_id`= ?";
        $data = $this->db_connection->select($sql, array($this->getID()));
        if(!$data)
        {return 0;}
        $data= $data[0];

        $this->setName($data['c_name']);
        $this->setE_mail($data['E-mail']);
        $this->setGender($data['gender']);
        $this->setDOB($data['dob']);
        $this->setDateOfRegsiter($data['dor']);
        $this->setPassword($data['password']);
        $this->setRegStatus($data['regstatus']);
        $this->setimgName($data['image']);
        return 1;
    }
    public function viewProfile ()
    {
        $this->connect();
        return $this->profile->GetProfileData($this->getID(),$this->db_connection);
    }
    public function EditProfile ($data)
    {
        $this->connect();
        return $this->profile->editProfile($this->getID(),$data,$this->db_connection);
    }

     /* class Methods */
    public  static function website_login ($E_mail,$password,$option , $fblogin=0)
    {
            if (filter_var($E_mail, FILTER_VALIDATE_EMAIL)&&preg_match("/^[-a-zA-Z0-9_.]*$/",$password))
            {
                $user = new Client();
                $user->connect();
                $query = "SELECT * FROM customer WHERE `E-mail` = ? and `password` = ? ";
                $Attributes = array($E_mail,$password);
                $result = $user->db_connection->select($query,$Attributes);
                if($result)
                {
                    $data = $result[0];
                    $user->setName($data['c_name']);
                    $user->setE_mail($data['E-mail']);
                    $user->setPassword($data['password']);
                    $user->setGender($data['gender']);
                    $user->setDOB($data['dob']);
                    $user->setDateOfRegsiter($data['dor']);
                    $user->setImage($data['image']);
                    $user->setRegStatus($data['regstatus']);
                    $user->setID($data['c_id']);
                    $user->setCoins($data['coins']);
                    $user->db_connection->disconnect();
                    if($fblogin)
                    {
                        $_SESSION['fbImage']=$user->getImage();
                    }

                    $_SESSION["Client"] = serialize($user);
                    if($option=='true')
                    {
                        // remember me button save E-mail & password in Cookie
                        $hour = time()+3600*24*30; // 30 day cookie
                        setcookie('E_mail', $E_mail, $hour);
                        setcookie('pass', $password, $hour);
                    }
                    return "login_success";
                }
            }
            return "login_fail";

    }

    public static function SignUp ($name,$E_mail,$password,$gender,$DOB , $type="normalLogin" , $fbuser = null , $fbpicture=null)
    {
        $db_connection = new Database();
        if($type=="normalLogin")
        {
            if (filter_var($E_mail, FILTER_VALIDATE_EMAIL))
            {
                $confirmCode = uniqid();
                $query = "INSERT INTO customer (`c_name`,`E-mail`,`password`,`gender`,`dob`,`regstatus`,`dor` , `confirmcode` , `image`) VALUES (?,?,?,?,?,?,?,?,'default.png') ";

                if($gender=="female")
                    $Attributes = array($name,$E_mail,$password,0,$DOB,0,date("Y-m-d") , $confirmCode);
                else
                    $Attributes = array($name,$E_mail,$password,1,$DOB,0,date("Y-m-d") , $confirmCode);
                $result = $db_connection->insert($query,$Attributes);
                if($result)
                {
                    //create profile
                        $id_query = "SELECT `c_id` from customer where `E-mail` = ?";
                        $id = $db_connection->select($id_query, array($E_mail));

                        $customer_id=$id[0]["c_id"];

                        $query= "INSERT INTO profile  VALUES (?, '' , '' , '' );";
                        $attributes = array($customer_id);
                        $db_connection->insert($query, $attributes);

                    // send validation code to mail
                        require '../../DashBoard[Back_End]/API_library/phpmailer/PHPMailerAutoload.php';
                        $to = $E_mail;
                        $subject = "I nuyasha freelancer Welcoming";
                        $body = "Welcome to join or website society <br> pls click on url below to validate your Account <br>";
                        $confirmCode= sha1($confirmCode);
                        $url ="http://localhost/Mis-Sw_Proj/FrontEnd/views/signup.php?code=$confirmCode";
                        $body.=$url;
                        //create new object of phpmailer API (web_service)
                        $mail = new PHPMailer();
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                        );

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
                         $mail->send();
                    // end of send valdidation code
                    return "sign_success";
                }
            }
            return "sign_fail";
        }
        //facebook login
        elseif($type=="facebooklogin")
        {
            // save user data
            if($fbuser->getEmail()==null){$facebookmail=$fbuser['name'].'@facebook.com';}
            else {$facebookmail=$fbuser->getEmail();}
            $query = "INSERT INTO customer (`c_name`,`E-mail`,`password`,`gender`,`image`,`dob`,`regstatus`,`dor`) VALUES (?,?,?,?,?,?,?,?) ";
            $gender=(($fbuser['gender']=="male")?"0":"1");
            $Attributes = array( $fbuser->getFirstName().' '.$fbuser->getLastName() , $facebookmail ,$fbuser['id'],$gender,$fbpicture['url'],'1992-10-8',1,date("Y-m-d"));
            $check =  $db_connection->insert($query,$Attributes);
            //create profile
            if($check==1){
            $id_query = "SELECT `c_id` from customer where `E-mail` = ?";
            $id = $db_connection->select($id_query, array($facebookmail));

            $customer_id=$id[0]["c_id"];

            $query= "INSERT INTO profile  VALUES (?, '' , '' , '' );";
            $attributes = array($customer_id);
            $db_connection->insert($query, $attributes);
            }
            return $check;
        }
    }
    public function updateClientStatus ($code)
    {
        $this->connect();
        $query = "SELECT * FROM customer WHERE SHA1(`confirmcode`) = ?";
        $Attributes = array($code);
        $data =  $this->db_connection->select($query,$Attributes);
        if($data)
        {
            $data=$data[0];
            $query = "UPDATE customer SET regstatus = 1 WHERE c_id = ?";
            $Attributes = array($data['c_id']);
            return $this->db_connection->insert($query,$Attributes);
        }
    }
    public static function chechMail ($E_mail)
    {
        $db_connection = new Database();
        $query = "SELECT * FROM customer WHERE `E-mail` = ?";
        $Attributes = array($E_mail);
        $result1 = $db_connection->select($query,$Attributes);
        $query = "SELECT * FROM manager WHERE `E-mail` = ?";
        $Attributes = array($E_mail);
        $result2 = $db_connection->select($query,$Attributes);
        if($result1==0 && $result2==0)
            return "new email";
        else
            return "existing email";

    }

    public function addToCart (Services $service , $date ,ShoppingCart $shoppingCart)
    {
        $this->connect();
        return $shoppingCart->AddtoCart($this->getID(), $service, $date, $this->db_connection);
    }

    public function deleteFromCart (ShoppingCart $shoppingCart,Services $service)
    {
      $this->connect();
      return $shoppingCart->deleteFromCart($service ,$this->getID(), $this->db_connection);
    }

    public function placeOrder (Order $o , Services $service)
    {
        $this->connect();
        return $o->placeOrder($this->getID(), $service, $this->db_connection);
    }
    public function estimateOrder (Order $o, Estimate $e)
    {
        $this->connect();
        return $o->estimateOrder($e, $this->getID(), $this->db_connection);
    }

    public function addService ($serviceName, $Description, $category, $tags, $Duration, $price, $FilesName, $FilesTmp_Name, $c_id/*Services $s , CustomerService $cs*/)
    {
        $s = new Services();
        return $s->addService($this->db_connection, $serviceName, $Description, $category, $tags, $Duration, $price, $FilesName, $FilesTmp_Name, $c_id);
    }

     public function UpdateServiceInfo(Services $service , $serviceName, $Description, $tags, $Duration, $price, $s_id)
    {
        return $service->UpdateServiceInfo($this->db_connection , $serviceName, $Description, $tags, $Duration, $price, $s_id);
    }
    public  function viewServices(Services $service , $cat_id , $where ="" , $from =0 , $to = 10)
    {
        return $service->viewAllServices($this->db_connection,$cat_id,$where,$from,$to);
    }
    public  function EstimatedServices(Services $service , $cat_id , $where ="" , $ordered="service.s_date", $way="DESC" , $from =0 , $to = 10)
    {
        return $service->viewServicesByEstimate($this->db_connection,$cat_id,$where,$ordered,$way,$from,$to);
    }
    public  function viewClientServices(Services $service , $c_id , $where ="" , $from =0 , $to = 10)
    {
        return $service->viewCustomerServices($this->db_connection,$c_id,$where,$from,$to);
    }

    /*override Methods */
    public function viewReport (CustomerService $m,$type)
    {
        $reciever = $this->getID();
        return  $m->viewMyReports($reciever,$this->db_connection , $type);
    }

    public function readMessage(Message $m ,$sender , $type) {
        return $m->readMessage($this->getID(),$sender,$this->db_connection,$type,1);
    }

    public function sendMessage(Message $m)
    {
        return $m->sendMessage($this->getID(),$this->db_connection);
    }
    public  function viewSpecificMessage ($Message , $antherUser , $date)
    {
        return $Message->viewSpecificMessage($this->getID(),$antherUser,$date,$this->db_connection,1);
    }

    public function customerServiceCounter(){
        $message = new CustomerService();
        return $message->countMessages($this->getID(),$this->db_connection,1);
    }

    public  function changeMessageStatus ($antherClient)
    {
        $message = new Customer_Msg();
        return $message->changeMessageStatus($antherClient,$this);
    }

    public  function changeMailStatus ($antherClient)
    {
        $message = new CustomerService();
        return $message->changeMessageStatus($antherClient,$this,1);
    }

    public function getContact(Customer_Msg $Msg,$sender){
        return $Msg->getContact($sender,$this->db_connection);
    }


    public function messageCounter(){
        $message = new Customer_Msg();
        return $message->countMessages($this->getID(),$this->db_connection);
    }
    public function getAllClients ($total , $db , $type)
    {
        $limit='';
        $status='';
        // check if enter specific row
        if($total=="0"){$limit='';}
        elseif($total!="All"){$limit='LIMIT '.$total.' ';}
        //check status of clients
        if($type=="Activated"){$status=' WHERE regstatus = 1 ';}
        elseif($type=="deActivated"){ $status=' WHERE regstatus = 0 ';}
        else { $status=" ";}

        $sql = "SELECT * FROM `customer` $status  ORDER by c_id DESC $limit ";
        $attributes= NULL;
        return $db->select($sql,$attributes);
    }
    //remove customer
    public function removeCustomer($db)
    {
        //remove customer image
        if(file_exists($this->getImage()))
        {
            unlink($this->getImage());
        }
        //get All service images to delete it
        $sql = "SELECT `service_image`.`image` as `SImage` FROM `service_image` , service
                WHERE service.`s_id`=`service_image`.`s_id` AND service.`c_id`=?";
        $attributes= array($this->getID());
        $data = $db->select($sql,$attributes);
        //remove all service images related to this customer
        if($data)
        {
            foreach ($data as $image)
            {
                if(file_exists(unlink($image['SImage'])))
                {
                    unlink($image['SImage']);
                }
            }
        }

        //finally delete customer from the system => cascading delete all services related to him
        $deletedQuery="delete from customer where `c_id` = ? ";
        $deltedAttributes= array($this->getID());
        return $db->delete($deletedQuery,$deltedAttributes);
    }

    public function getSpecificCustomer($db)
    {
        $sql="SELECT customer.* FROM customer WHERE customer.`c_id` = ? ";
        $attributes = array($this->getID());
        return $db->select($sql,$attributes);
    }
    public function getSpecificCustomerservices ($db)
    {
        $sql=" SELECT name , `s_id` , `cat_id` FROM service WHERE service.`c_id`= ? ";
        $attributes = array($this->getID());
        return $db->select($sql,$attributes);
    }

    /*Show Top Services*/
    public function viewTopService ($service , $option="")
    {
        return $service->viewTopService($this->db_connection , $option);
    }
    /*show top services in each category*/
    function TopCategory($category , $option="")
    {
        return $category->view_top_category($this->db_connection , $option);
    }
    /*view category*/
    public function viewMainCategory($category)
    {
        return $category->viewMainCategory($this->db_connection);
    }
    public function viewSubCategory($category , $parentID)
    {
        return $category->viewsubCategory($this->db_connection , $parentID);
    }

    /**Get system Emails*/
    public function getSystemEmails($system)
    {
        return $system->getmails($this->db_connection);
    }
    public function getSystemfaxes($system)
    {
        return $system->getfaxes($this->db_connection);
    }
    public function getSystemphones($system)
    {
        return $system->gettelphone($this->db_connection);
    }
    public function getSystemaddresses($system)
    {
        return $system->getadresses($this->db_connection);
    }
    /*Send Feedback to Website*/
    public function SendFeedback($from , $subject , $msg )
    {
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

       $mail->setFrom($from);
       $mail->addAddress('hossamhsb96@gmail.com');     // Add a recipient
       $mail->addReplyTo($from);

       $mail->isHTML(true);                                  // Set email format to HTML

       $mail->Subject = $subject;
       $mail->Body    = $msg;
       if(!$mail->send())
       {
           return false;
       }
       else
       {
           return true;
       }
    }
    //show selected services
    public function showSelectedServices(Services $service)
    {
        $this->connect();
        $value = $service->showSelectedServices($this->db_connection);
        $data=$value[0];
        if($data){
            $service->setService_name($data['name']);
            $service->setService_view($data['view']);
            $service->setStatus($data['status']);
            $service->setTags($data['tags']);
            $service->setPrice($data['price']);
            $service->setDeliver_date($data['d_date']);
            $service->setService_date($data['s_date']);
            $service->setDescription($data['description']);
            $service->setImages($this->db_connection);
            $service->setType($data['cat_id'],$this->db_connection);
            $service->setCompletedOrders($data['completed_order']);
            $service->setBindingOrders($data['uncompleted_order']);

            $client = new Client();
            $service->setOwnerID($data['c_id'], $client);
            return $client;
        }
    }
    //show service Estimates
    public function showServiceEstimates($estimate ,$service)
    {
        $this->connect();
        return $service->showServiceEstimates($estimate,$this->db_connection);
    }
    // update coins
    public function updateCoinse()
    {
      $this->connect();
      $sql = "UPDATE `customer` SET `coins`=? WHERE `c_id`=?";
      $Attibutes=array
      ($this->getCoins(),$this->getID());
      $check =  $this->db_connection->update($sql,$Attibutes);
      return $check;
    }

    /********************* Payment methods ********************************/
    public function Transaction_payment ($transaction , $paymentMethod)
    {
        $check_payment_method = $transaction->doPay($paymentMethod , $this->db_connection);
        // first we check if credit card or paypal saved into mysql
        if($check_payment_method)
        {
            $paymenth_type = '';
            if (is_a($paymentMethod, 'CreditCard')) {$paymenth_type="CreditCard";}
            else if (is_a($paymentMethod, 'Paypal')) {$paymenth_type="Paypal";}
            // check if transaction data saved correctly
            $check_tran = $transaction->saveTransaction_data($paymenth_type , $paymentMethod->getID() , $this->getID(), $this->db_connection);
            if($check_tran)
            {
                return $check_tran;
            }
            else{return $check_tran;}
        }
        else
        {
            return $check_payment_method;
        }
    }
    public function Transaction_withdraw ($id , $transaction)
    {

        $user = new Client();
        $user->connect();
        $coin=$user->viewcoins($id);
        foreach ($coin as $key) {
        $lift=$key['coins'];
        }
        $new=$lift-$transaction;
        if($new>=0){
        $sql="UPDATE  `customer` SET `coins`=$new WHERE `c_id`=$id";

        return  $user->db_connection->update($sql,null);;
        }
        else{return 0;}
    }

    public function getMyTransactions($transaction)
    {
        return $transaction->getMyTransactions($this->getID() , $this->db_connection);
    }

    public function RemoveService(Services $service) {

    }
    public function serviceCounter_view (Services $service)
    {
        $this->connect();
        return $service->serviceCounter_view($this->db_connection);
    }

}
