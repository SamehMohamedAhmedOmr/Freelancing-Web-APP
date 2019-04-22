<?php
require './includes/header.php';
if(!isset($_SESSION["Client"])&& $client->getRegStatus() != 0)
{
    header("Location: Home.php");
    exit();
}
$customerMessage = new Customer_Msg();
$allSendingOne = $client->readMessage($customerMessage,null,null);
?>

<script>document.title="Massenger";</script>
<section class="">

    <div class="ThirdSectionHeader">
        <h2>Massenger</h2>
    </div>
<?php

    if(isset($_SESSION['msg_status_Faild'])){
    echo $_SESSION['msg_status_Faild'];
    unset($_SESSION['msg_status_Faild']);
    }
    elseif(isset($_SESSION['message_error'])){
    echo $_SESSION['message_error'];
    unset($_SESSION['message_error']);
    }

if($allSendingOne){
    $viewInitalData = $client->viewSpecificMessage($customerMessage,$allSendingOne[0]['c_id'],null);
    $contactReturn = $client->getContact($customerMessage,$allSendingOne[0]['c_id']);
    $contactData = $contactReturn[0];
?>

    <div class="container">
      <div class="row chatdiv">

        <div class="jumbotron col-sm-5 userlistdiv" style="border-radius: 6px 0px 0px 6px;">

            <?php
            $length= sizeof($allSendingOne);
            $counter=0;
                foreach ($allSendingOne as $value) {
                    $counter++;
                   echo '
                    <a class="clientAnchor" href="MassengerBox.php#messageData"
                             onClick="newChat('.$value['c_id'].')">
                          <div class="row clientDiv" style="">
                          ';
                            if(preg_match('/https/',$value['image']))
                            {
                                echo '<img src="'.$value['image'].'" class="img-circle img_position">';
                            }
                            else
                            {
                                echo '<img src="../Images/client_images/'.$value['image'].'" class="img-circle img_position">';
                            }

                            echo '<div class="clientDetail">
                                <h4 class="">'.$value['c_name'].'</h4>
                                <h6 class="limited-text" >'.$value['msg'].'</h6>
                              </div>
                          </div>
                    </a>';

                          if ($counter<=$length-1) {
                           echo '<br></br> <br>';
                          }

                }

             ?>
        </div>


        <div class="jumbotron col-sm-8 MessageDiv" style="border-radius: 0px 6px 6px 0px;">

            <div class="chatdiving">

                <h4 class="well clientHeader" style="border-radius: 0px 0px 0px 0px;" >
                    <a name="clientAnchor" href="#">
                    <?php
                   echo $contactData['c_name'];
                     ?>
                    </a>
                </h4>


            <div class="form-group" id="messageData"
                     name = "<?php echo $contactData['c_id'];  ?>">
                <div class="form-control ShowMessage" placeholder="Message Box">
                        <?php
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
                        $return = $client->changeMessageStatus($contactData['c_id']);
                        ?>
                </div>
            </div>

            </div>

            <div class="form-group">
                <textarea id="usermsg" type="text" class="form-control messageData"  placeholder="Enter your Message"  name = "<?php echo $viewInitalData[0]['c_id'];  ?>"
                 style="border-radius: 0px 0px 6px 0px;"></textarea>
            </div>

        </div>

      </div>

    </div>
<?php
    unset($_SESSION['currentChat']);
}
else {
 ?>

<div class="container">
    <div class="jumbotron" style="background-color: white;">
        <img src="../Images/website_images/no_message.jpg">
    </div>
</div>

<?php } ?>
</section>
<?php
require './includes/footer.php';
