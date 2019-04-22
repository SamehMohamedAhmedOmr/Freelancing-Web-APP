<?php 
require './includes/header.php';
if(isset($_SESSION["Client"]))
{
    $client = unserialize($_SESSION["Client"]);
}
else {
  header("Location: Home.php");
  exit();
}
$customerservice = new CustomerService();
?>
<script>document.title="Web-Site Contact";</script>
    <div class="ThirdSectionHeader">
        <h2>Web-Site Contact</h2>
    </div>

<div id="Mail" class="tab_content container"> <!-- start mailbox -->
    <div class="content-wrapper" style="margin-bottom: 100px;">
      <div class="container-fluid">
        <div class="card mb-3">
          <div class="card-header" style="background-color: #F25C27; color:white;">
                <i class="fa fa-table" style="color: white;"></i> Web-Site Contact
          </div>

            <?php
            if(isset($_GET['reciever']) && isset($_GET['sender'])
                 && isset($_GET['date']) && isset($_GET['type']))
            {

                if($_GET['type']==1){
                  $sender = $_GET['reciever'];
                  $reciever = $_GET['sender'];
                }
                else{
                  $sender = $_GET['sender'];
                  $reciever = $_GET['reciever'];
                }

               $data = $client->viewSpecificMessage($customerservice,$sender,$_GET['date']);
                
                if($data!=NULL)
                {
                    $data=$data[0];
                    if($data['status']==0)
                    {
                $client->changeMailStatus($data['c_id'].'*'.$data['mgr_id'].'*'.$data['date']);
                    }
                          if($_GET['type']==1){
                            $sender = $data['reciever'];
                            $receiver = $data['sender'];
                          }
                          else{
                            $sender = $data['sender'];
                            $receiver = $data['reciever'];
                          }
                ?>
            <div>
                <div class="col-xs-10 col-xs-offset-1" style="padding-left: 0;">
                    <div class="msgheader msgInfo" style="margin-top: 30px;">
                    <span><h4>subject: <small> <?php echo $data['subject'] ?> </small></h4></span>
                    <span>from: <small> <?php echo $sender ?> &emsp;&emsp;</small></span>
                    <span>to: <small> <?php echo $receiver?> </small> </span><br/>
                    <span style="position: absolute;margin-top: 10px;">
                      <small> <?php echo $data['date']?> </small> 
                    </span>
                </div>
                <!-- <hr style="width:70%; border:.5px dashed #ddd;"> -->
                <textarea class="msgContent col-xs-12" readonly><?php echo $data['content'];?></textarea>

<?php             
               }
     
        }
        else
           {
               //search bar
              ?> 
                <div class="options col-xs-12" style="margin-bottom: 25px;">
                   <div class="col-xs-9">
                       <button class="btn btn-default My_messages col-xs-1"
                        onclick="view_customerInbox()">
                       Inbox</button>
                       
                    <!--<button class="btn btn-default send_messages" 
                        onclick="view_customerSendingMsg()">
                       Sent Messages</button>-->

                       <button class="btn btn-default send_messages col-xs-3"
                        onclick="view_servicesAccepted()">Accepted Services</button>

                       <button class="btn btn-default send_messages col-xs-3" 
                        onclick="view_unacceptedServices()">Non-activated Services</button>

                       <button class="btn btn-default send_messages col-xs-2" 
                        onclick="viewReport()">Reports</button>

                   </div>

                   <div class="mailSearchInp input-group col-xs-3">
                       <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                       <input type="text" class="form-control input-lg searchMailBox" placeholder="Search Mails" aria-describedby="basic-addon1"
                        onkeyup="searchCustomerbox(this.value)">
                   </div>

               </div>
                <div class="card-body">
                    <div class="mailboxTable table-responsive">
                       <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead class="mailBox_header">
                               <tr>
                                   <th >Supervisor</th>
                                   <th >message</th>
                                   <th >Date</th>
                               </tr>
                           </thead>
                       <tbody class="mailbox">
                        <?php 
                        $customerservice->setKind(4);
               $data = $client->readMessage($customerservice,null,'All');
               if($data!=NULL)
               {
                  if(is_array($data))
                  {
                        foreach ($data as $value) 
                        {
                          echo ' 
                           <tr
                            onclick="viewSpecificCustomerMessage('.$value['mgr_id'].' ,'
                           .$value['c_id'].',\''.$value['date'].'\')"
                               '.(($value['status']==0)?"class=\"unread\" ":"").' class="messageBoxhover">
                             <td style="width:10%;" class="sender" >'.$value['senderName'].'</td>
                             <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['content'].'</td>
                             <td style="width:15%" class="date">'.$value['date'].'</td>
                           </tr>
                        '; 
                       }                            
                 }
              }
              else
               {            echo 
            '<tr>                     
                <td colspan="3" class="no_message_found">No Message Found</td>
            </tr>';  
               
               }
           } 
        ?>
            </tbody>
        </table>
    </div>
    </div>
        <div class="card-footer small text-muted" style="background-color: #F25C27;">
          <span style="color: white;">
                Updated at <?php $date = date('Y-m-d H:i'); echo $date; ?>
          </span>
        </div>
        </div>
    </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->     
</div>


</div>  <!-- end mailbox -->
<?php 
require './includes/footer.php'; 