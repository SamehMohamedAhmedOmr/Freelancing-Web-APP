<?php
session_start();
require '../config/includes.php';
if($_SERVER['REQUEST_METHOD']=='POST')
{
    // signUp
    if( $_POST['action']=='signUp' && isset($_POST['Name']) && isset($_POST['Email']) && isset($_POST['pass']) && isset($_POST['gender']) && isset($_POST['DOB']))
    {
        $name = $_POST['Name'];
        $E_mail = $_POST['Email'];
        $password = $_POST['pass'];
        $gender = $_POST['gender'];
        $DOB = $_POST['DOB'];
        echo Client::SignUp($name,$E_mail,$password,$gender,$DOB);
    }
    // check if the mail exists in the databae
    if( $_POST['action']=='check_mail' && isset($_POST['Email']))
    {
        $E_mail = $_POST['Email'];
        echo Client::chechMail($E_mail);
    }
    // login of customer
    if( $_POST['action']=='client_login' && isset($_POST['Email']) && isset($_POST['pass']) && isset($_POST['option']))
    {
        $E_mail = $_POST['Email'];
        $password = $_POST['pass'];
        $option = $_POST['option'];
        echo Client::website_login($E_mail, $password, $option);
    }

    // Edit Service
    if( $_POST['action']=='EditService' && isset($_POST['serviceName']) && isset($_POST['Description']) && isset($_POST['tags']) && isset($_POST['Duration']) && isset($_POST['price']) && isset($_POST['s_id']))
    {
        $serviceName = $_POST['serviceName'];
        $Description = $_POST['Description'];
        $tags = $_POST['tags'];
        $Duration = $_POST['Duration'];
        $price = $_POST['price'];
        $s_id = $_POST['s_id'];
        $client = unserialize($_SESSION["Client"]);

        echo $client->UpdateServiceInfo(new Services(),$serviceName, $Description, $tags, $Duration, $price, $s_id);
    }

    /*--------------------Shopping Cart---------------------*/
    if($_POST["action"]=="ShoppingCartDel" && isset($_POST["c_id"]) && isset($_POST["s_id"]))
    {
       $client = unserialize($_SESSION['Client']);
       if(isset($_COOKIE['ShoppingCart']))
       {
         unset($_COOKIE['ShoppingCart']);
         setcookie('ShoppingCart', '' ,time()-3600);
       }
       $sc = new ShoppingCart();
       $service = new Services();
       $service->setService_id($_POST['s_id'],$client->db_connection);
       $check = $client->deleteFromCart($sc,$service);
       // $check = $sc->deleteFromCart($_POST["s_id"],$_POST["c_id"],$client->db_connection);
       echo $check;
    }
     if($_POST["action"]=="ShoppingCartDel_cookie" && isset($_POST["key"]))
    {
      if(isset($_COOKIE['ShoppingCart']))
      {
        $key = $_POST["key"];
        $ClientShppingCart = unserialize($_COOKIE['ShoppingCart']);
        unset($ClientShppingCart[$key]);
        echo setcookie ("ShoppingCart",serialize($ClientShppingCart),time()+ 86400*30,"/"); // 1 day
      }
      else {
        echo 0;
      }
    }
    if($_POST["action"]=="updateCoins" && isset($_POST["c_id"]) && isset($_POST["s_price"]) && isset($_POST["s_id"]))
    {
      $client = unserialize($_SESSION['Client']);
      // Update coins value
      $client->setCoins($client->getCoins() - $_POST["s_price"]);
      $check = $client->updateCoinse();
      // Create/Place order
      $o = new Order();
      $sc = new ShoppingCart();
      $service = new Services();
      $service->setService_id($_POST['service_id'],$client->db_connection);
      $check =  $sc->placeServiceToOrder($o , $client , $service);

      $client->db_connection->disconnect();
      $_SESSION['Client'] = serialize($client);
      echo $check;
    }
    //--------------------Estimate Order---------------------
    if($_POST["action"]=="addEstimate" && isset($_POST["o_id"]) && isset($_POST["s_id"]))
    {
      $client = unserialize($_SESSION['Client']);

      $e = new Estimate();
      $e->setComment($_POST["comment"]);
      $e->setStar($_POST["stars"]);
      $e->setDate(date('Y-m-d H:i:s'));

      $o = new Order();
      $o->setOrder_id($_POST["o_id"]);
      echo $client->estimateOrder($o,$e,$_POST["s_id"]);
    }
    // ----------------------Add to shopping cart ----------------
    if($_POST['action']=="addToshoppingCart" && isset($_POST['client_id']) && isset($_POST['service_id']) && isset($_POST['date']))
    {
        $client='';
        if(isset($_SESSION['Client']))
        {$client = unserialize($_SESSION['Client']);}
        else {$client= new Client(); $client->setID(-1); $client->connect();}
        $shoppingCart = new ShoppingCart();
        $service = new Services();
        $service->setService_id($_POST['service_id'],$client->db_connection);
        echo  $client->addToCart($service ,$_POST['date'] , $shoppingCart);
    }
    // ----------------------Place order----------------
    if($_POST['action']=="placeOrder" && isset($_POST['service_id']))
    {
        if(isset($_SESSION['Client']))
        {
            $client = unserialize($_SESSION['Client']);
            // Update coins value
            $client->setCoins($client->getCoins() - $_POST["price"]);
            $check = $client->updateCoinse();

            $order = new Order();
            $service = new Services();
            $service->setService_id($_POST['service_id'],$client->db_connection);
            $check = $client->placeOrder($order ,$service);

            $client->db_connection->disconnect();
            $_SESSION['Client'] = serialize($client);
            echo $check;
        }
        else
        {
            return 0;
        }
    }
    // update service view
    if($_POST['action']=="seriveViewUpdate" && isset($_POST['s_id']))
    {
       $client = unserialize($_SESSION['Client']);
       $service = new Services();
       $service->setService_id($_POST['s_id'] , $client->db_connection);
       $check = $client->serviceCounter_view($service);
       if($check==1)
       {echo '<result>service_view_updated</result>';}
       else {echo '<result>service__view_notUpdated</result>';}
    }

    if($_POST['action']=='view_Customerinbox')
    {
        $client = unserialize($_SESSION['Client']);
        $customerService = new CustomerService();
        $customerService->setKind(4);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $client->readMessage($customerService,null , 'All');}
        else {$data = $client->readMessage($customerService,null ,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value)
                {
                   echo '
                    <tr onclick="viewSpecificCustomerMessage('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\')"
                        '.(($value['status']==0)?"class=\"unread\" ":"").' >
                      <td style="width:10%;" >'.$value['senderName'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 ';
                }
            }
        }
        else
        {
            echo
            '<tr>
                <td colspan="3" class="no_message_found">No Message Found</td>
            </tr>';
        }
    }

        // view my send messages
    if($_POST['action']=='site_customer_msg')
    {
        $client = unserialize($_SESSION['Client']);
        $customerService = new CustomerService ();
        $customerService->setKind(3);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $client->readMessage($customerService,$client->getID(),'All');}
        else {$data = $client->readMessage($customerService,$client->getID() ,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value)
                {
                   echo '
                    <tr onclick="viewSpecificCustomerMessage
                            ('.$value['c_id'].' ,'.$value['mgr_id'].',\''.$value['date'].'\',1)"
                                '.(($value['status']==0)?"class=\"unread\" ":"").'>

                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 ';
                }
            }
        }
        else
        {
            echo '  <tr>
                    <td colspan="3" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

            // accepted Services
    if($_POST['action']=='service_Accepted')
    {
        $client = unserialize($_SESSION['Client']);
        $customerService = new CustomerService ();
        $customerService->setKind(1);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $client->readMessage($customerService,$client->getID(),'All');}
        else {$data = $client->readMessage($customerService,$client->getID() ,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value)
                {
                   echo '
                    <tr onclick="viewSpecificCustomerMessage
                            ('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\')"
                                '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 ';
                }
            }
        }
        else
        {
            echo '  <tr>
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

                // view my send messages
    if($_POST['action']=='service_unAccepted')
    {
        $client = unserialize($_SESSION['Client']);
        $customerservice = new CustomerService ();
        $customerservice->setKind(2);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $client->readMessage($customerservice,$client->getID(),'All');}
        else {$data = $client->readMessage($customerservice,$client->getID() ,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value)
                {
                   echo '
                    <tr onclick="viewSpecificCustomerMessage
                            ('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\')"
                             '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 ';
                }
            }
        }
        else
        {
            echo '  <tr>
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

    if($_POST['action']=='viewReport')
    {
        $client = unserialize($_SESSION['Client']);
        $customerservice = new CustomerService ();
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $client->viewReport($customerservice,'All');}
        else {$data = $client->viewReport($customerservice,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value)
                {
                   echo '
                    <tr onclick="viewSpecificCustomerMessage
                            ('.$value['c_id'].' ,'.$value['mgr_id'].',\''.$value['date'].'\',1)"
                             '.(($value['status']==0)?"class=\"unread\" ":"").' >
                      <td style="width:25%;" >'.$value['senderName'].'</td>

                      <td style="width:25%;" >'.$value['serviceName'].'</td>
                      <td style="width:25%;">
                      <b>'.$value['subject'].'</b>- '.$value['content'].'
                      </td>
                      <td style="width:25%">'.$value['date'].'</td>
                    </tr>
                 ';
                }
            }
        }
        else
        {
            echo '  <tr>
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }
}


if($_SERVER['REQUEST_METHOD']=='GET')
{
    if( $_GET['action']=='contact' && isset($_GET['owner']) && isset($_GET['messageContent']) )
    {
        $client = unserialize($_SESSION['Client']);
        $owner = $_GET['owner'];
        $message = $_GET['messageContent'];

        $error;
        $Customer_Msg = new Customer_Msg();
        $Customer_Msg->setDate();
        $Customer_Msg->setStatus(0);

        // validate notification
        $check= $Customer_Msg->setMessage($message);
        if($check!== true){$error[] = $check;}

        $check = $Customer_Msg->setTo($owner, $client->db_connection);
        if($check!== true){$error[] = $check;}

        // if there is validation error dont save message and display errors , else save message
        if(!isset($error))
                {
           $check = $client->sendMessage($Customer_Msg);
           if($check==1)
           {$_SESSION['msg_status_success']='<p class="input_validate_sucess">your message has been sent sucesfully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>';}
           else{$_SESSION['msg_status_Faild']='<p class="input_validate_error">oops ! pls try send message again <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';}
        }
        else
        {
             $_SESSION['message_error']='';
            foreach ($error as $value) {
                $_SESSION['message_error'].='<p class="input_validate_error">'.$value.' <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }
        }
    }

   // Defualt Search
    if($_GET['action']=='Defualt' && isset($_GET['cat_id']))
    {
        $cat_id = $_GET['cat_id'];
        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->viewServices($service, $cat_id);

        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }


     // Search for service
    if($_GET['action']=='SearchForService' && isset($_GET['keyword']) && isset($_GET['cat_id']))
    {
        $cat_id = $_GET['cat_id'];
        $searchKey = $_GET['keyword'];
        $where = "And (service.name LIKE '%$searchKey%' or service.tags LIKE '%$searchKey%')";
        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->viewServices($service, $cat_id, $where);

        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }


    // Search for service By Estimate
    if($_GET['action']=='SearchByEstimate' && isset($_GET['value']) && isset($_GET['cat_id']))
    {
        $cat_id = $_GET['cat_id'];
        $value = $_GET['value'];
        $where = "And estimate.stars = $value";
        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->EstimatedServices($service, $cat_id, $where);

        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }

    // ordering service
    if($_GET['action']=='orderServices' && isset($_GET['cat_id']) && isset($_GET['order']) && isset($_GET['way']) )
    {
        $cat_id = $_GET['cat_id'];
        $order = $_GET['order'];
        $way = $_GET['way'];
        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->EstimatedServices($service, $cat_id, '', $order, $way);

        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }

    // search for service by estimate and order it
    if($_GET['action']=='estimateAndOrder' && isset($_GET['cat_id']) && isset($_GET['value']) && isset($_GET['order']) && isset($_GET['way']) )
    {
        $cat_id = $_GET['cat_id'];
        $value = $_GET['value'];
        $where = "And estimate.stars = $value";
        $order = $_GET['order'];
        $way = $_GET['way'];
        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->EstimatedServices($service, $cat_id, $where, $order, $way);

        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }

    // getServicesPrices
    if($_GET['action']=='showPrice' && isset($_GET['cat_id']) && isset($_GET['min']) && isset($_GET['max']))
    {
        $max = $_GET['max'];
        $min = $_GET['min'];
        $cat_id = $_GET['cat_id'];
        $where = " and service.price >= $min and service.price <= $max";

        $service = new Services();
        $client = new Client();
        $client->connect();
        $result = $client->viewServices($service, $cat_id, $where);
        if($result)
        {
            foreach ($result as $value)
            {
                echo '<!--start service -->
                    <div class="item showService col-xs-4">
                        <div class="thumbnail item-box">
                            <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                            <div class="overlayContainer">
                                <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                <div class="overlay"></div>
                            </div>
                            <div class="caption">
                                <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                <p>'.$value['description'].'</p>
                                <span class="price">'.$value['price'].' $</span>
                                <div class="timeContainer">
                                    <span class="date">'.$value['s_date'].'</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--End services -->';
            }
        }
        else
        {
            echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
        }
    }


    if( $_GET['action']=='openNewChat' && isset($_GET['receiverID']))
    {
        $client = unserialize($_SESSION['Client']);
        $receiverID = $_GET['receiverID'];

        $Customer_Msg = new Customer_Msg();
        $viewInitalData = $client->viewSpecificMessage($Customer_Msg,$receiverID,null);
        $contactReturn = $client->getContact($Customer_Msg,$receiverID);
        $contactData = $contactReturn[0];

        if ($viewInitalData) {
            echo '
            <div class="chatdiving">
                <h4 class="well clientHeader" style="border-radius: 0px 0px 0px 0px;" >'.

                   $contactData['c_name']

              .'</h4>

            <div class="form-group" id="messageData" name ="'.$receiverID.'">
                <div class="form-control ShowMessage" placeholder="Message Box"> ';

                        foreach ($viewInitalData as  $value) {
                            if ($value['reciever'] == $client->getID()) {

                                if(preg_match('/https/',$contactData['image']))
                                {
                                echo '<img src="'.$contactData['image'].'"class="img-circle img_chat_position">';
                                }
                                else
                                {
                                echo '<img src="../Images/client_images/'.$contactData['image'].'"class="img-circle img_chat_position">';
                                }
                                echo '<p class="well well-sm messagetext">'.$value['msg'].'</p> <br>';
                            }
                            else{
                                echo '<p class="well well-sm pull-right sendertext">
                                        '.$value['msg'].'</p> <br><br><br>';
                                }
                            }

             echo '</div>
            </div>

            </div>
            ';
         $return = $client->changeMessageStatus($contactData['c_id']);

        }
    }

}
