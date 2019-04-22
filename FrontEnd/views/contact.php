<?php 
require './includes/header.php';
$system = new System();

$Emails = $client->getSystemEmails($system);
$faxes = $client->getSystemfaxes($system);
$phones = $client->getSystemphones($system);
$addresses = $client->getSystemaddresses($system);

/**********************Send Feedback************************/
if($_SERVER['REQUEST_METHOD']=="POST")
{
    $Success = '';
    $Error = array();
   if(isset($_POST['msg']) && isset($_POST['from_mail']) && isset($_POST['subject'])) 
   {
       //include php mailer librariy
       require '../../DashBoard[Back_End]/API_library/phpmailer/PHPMailerAutoload.php';
       
       //validate Input       
                                    /* 1 - Validate E-mail*/
       if(!filter_var($_POST['from_mail'], FILTER_SANITIZE_EMAIL)){$Error[]='please enter valid E-mail';}
       else
       {
            if(strlen(trim($_POST['from_mail'])) < 8 ){$Error[]='E-mail Address is too short';}
            elseif(strlen(trim($_POST['from_mail'])) >50 ){$Error[]='E-mail Address is too long';}
       }
       
                                    /* 2 - validate subject*/
       if(!filter_var($_POST['subject'], FILTER_SANITIZE_STRING)){$Error[]='please enter valid subject';}
       elseif(empty($_POST['subject']) ||ctype_space($_POST['subject']) ) {$Error []='subject can\'t be empty ';}
       
                                    /* 3 - Validate Message*/       
       if(!filter_var($_POST['msg'], FILTER_SANITIZE_STRING)){$Error[]='please enter valid message';}
       elseif(empty($_POST['msg']) ||ctype_space($_POST['msg']) ) {$Error []='message can\'t be empty ';}
       
       
       if(empty($Error))
        {
            $check = $client->SendFeedback($_POST['from_mail'] , $_POST['subject'] ,$_POST['msg']);
            if($check==false)
            {$_SESSION['feedbackError'] ='message coudn\'t be send right now pls try again later';}
            else
            {$Success = ' Your message was send Succesfully';}
       }
    else 
      {
           $_SESSION['feedbackError']='';
           foreach ($Error as $data) {
               $_SESSION['feedbackError'] .='<p class="contactmsgerror">'.$data . '<i class="fa fa-times-circle customizecontactError pull-right" aria-hidden="true"></i></p>';
           }
      }
    }
    if($Success!=''){$_SESSION['feedbackSucess']='<p class="contactmsgsuccess">'.$Success.'<i class="fa fa-check pull-right customizecontactsucess" aria-hidden="true"></i></p>';} 
    header("Location: contact.php");
    exit();
}
// display Feedback Messages
if(isset($_SESSION['feedbackError'])){echo $_SESSION['feedbackError'];unset($_SESSION['feedbackError']);}
if(isset($_SESSION['feedbackSucess'])){echo $_SESSION['feedbackSucess'];unset($_SESSION['feedbackSucess']);}
?>
<div class="contactusFirstDiv">
    <div class="ThirdSectionHeader" style="padding: 20px 0px 20px 0px;">
        <h2>Contact US </h2>
    </div>
    <div class="container">
        <!-- start Contact Options-->
        <div class="row  contactContainer" style="margin-bottom: 120px;">
                <div class="col-sm-offset-0 col-sm-3 col-xs-offset-2 col-xs-8 toogletofaq">
                    <div class="contactItem">
                        <h3>FAQ'S<span class="fa fa-question-circle-o pull-right"></span></h3>
                    </div>
                </div>
                <div class="col-sm-offset-0 col-sm-3 col-xs-offset-2 col-xs-8">
                    <div class="contactItem">
                        <h3>Contact us<span class="fa fa-phone-square pull-right"></span></h3>
                    </div>
                </div>
                <div class="col-sm-offset-0 col-sm-3 col-xs-offset-2 col-xs-8">
                    <div class="contactItem">
                        <h3>Payment<span class="fa fa-usd pull-right"></span></h3> 
                    </div>
                </div>
                <div class="col-sm-offset-0 col-sm-3 col-xs-offset-2 col-xs-8">
                    <div class="contactItem">
                        <h3>FeedBack<span class="fa fa-calendar-check-o pull-right"></span></h3>
                    </div>
                </div>
            </div>

            <!-- start faq's-->
            <div class="faqs" style="margin-bottom: 120px;">
                <h3 class="text-center text-warning" style="margin-bottom: 20px;">Popular Questions (FAQ'S)</h3>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-warning" >
                      <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           How do I pay on Jumia?
                          </a>
                        </h4>
                      </div>
                      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                         You can choose from the different payment methods available on Jumia. 
                         <br>Please find below the list of available payment methods:<br>
                        Cash On Delivery (easy and simple at your doorstep)<br>
                        Credit Card
                        </div>
                      </div>
                    </div>

                    <div class="panel panel-warning">
                      <div class="panel-heading" id="headingtwo">
                        <h4 class="panel-title">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo" aria-expanded="false" aria-controls="collapsetwo">
                           Do I need an account to shop on Jumia?
                          </a>
                        </h4>
                      </div>
                      <div id="collapsetwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingtwo">
                        <div class="panel-body">
                          No, you don’t need to have already an account created.
                        </div>
                      </div>
                    </div>


                    <div class="panel panel-warning">
                      <div class="panel-heading" id="headingOne">
                        <h4 class="panel-title">
                          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsethree" aria-expanded="false" aria-controls="collapsethree">
                           Are all products on Jumia original and genuine?
                          </a>
                        </h4>
                      </div>
                      <div id="collapsethree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                         es. We are committed to offering our customers only 100% genuine and
                         original products. We also take all necessary actions to ensure this: 
                         any seller found to be selling non-genuine products is immediately delisted 
                         from Jumia.
                        </div>
                      </div>
                    </div>

                    <div class="panel panel-warning">
                      <div class="panel-heading" id="headingOne">
                        <h4 class="panel-title">
                          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                           How can I track my order?
                          </a>
                        </h4>
                      </div>
                      <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                          We will send you regular updates about the status of your order via emails and SMS.
                          After your order has left our warehouse and is on its way to you, you can also track its
                          status by entering your tracking number on here.
                        </div>
                      </div>
                    </div>

                </div>
        </div>

        <!-- start contact us-->
        <div class="contactUs" style="margin-bottom: 120px;">
            <h3 class="text-center text-warning header" style="margin: 40px auto;">Contact US (Phones or E-mail)</h3>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="byphone">
                        <h4>By Phones<span class="fa fa-phone"></span></h4>
                        <ul>
                            <li>Telephone:
                                <ul>
                                    <?php
                                    if($phones)
                                    {
                                        echo '<ul>';
                                        foreach ($phones as $value) {
                                            echo '<li>'.$value['phone_numbers'].'</li>'; 
                                        }
                                        echo '</ul>';
                                    }
                                    else { echo '<li>there is no phones</li>';}
                                    ?>
                                </ul>
                            </li>
                            <li>Fax:
                                <ul>
                                    <?php
                                    if($faxes)
                                    {
                                        echo '<ul>';
                                        foreach ($faxes as $value) {
                                            echo '<li>'.$value['faxes'].'</li>'; 
                                        }
                                        echo '</ul>';
                                    }
                                    else { echo '<li>there is no faxes</li>';}
                                    ?>
                                </ul>                            
                            </li>
                            
                            <li>
                                E-mails:
                                <ul>
                                    <?php
                                    if($Emails)
                                    {
                                        echo '<ul>';
                                        foreach ($Emails as $value) {
                                            echo '<li>'.$value['emails'].'</li>'; 
                                        }
                                        echo '</ul>';
                                    }
                                    else { echo '<li>there is no E-mails</li>';}
                                    ?>
                                </ul> 
                            </li>
                            <li>
                                Addresses:
                                <ul>
                                    <?php
                                    if($addresses)
                                    {
                                        echo '<ul>';
                                        foreach ($addresses as $value) {
                                            echo '<li>'.$value['adresses'].'</li>'; 
                                        }
                                        echo '</ul>';
                                    }
                                    else { echo '<li>there is no Addresses</li>';}
                                    ?>
                                </ul> 
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="bymail">
                        <h4>Contact us by E-mail<span class="fa fa-envelope-o"></span></h4>
                        <form class="form-group" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input name="from_mail" type="text" placeholder="enter your E-mail" class="form-control">
                            <input name="subject" type="text" placeholder="Subject" class="form-control">
                            <textarea name="msg" class="form-control" placeholder="enter your msg"></textarea>
                            <input type="submit" value="send message" class="btn btn-warning btn-block" style="background-color: #f25c27;">
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<?php 
require './includes/footer.php'; 
