<?php 
include('includes/Header.php');
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
?>
<div id="Mail" class="tab_content container"> <!-- start mailbox -->
  <div class="content-wrapper">
      <div class="container-fluid">
        <div class="card mb-3">
          <div class="card-header">
                <i class="fa fa-table"></i> Customer Service

                <a href="../views/customerServiceMsg.php" class="btn btn-primary btn-success">
                    <span class="glyphicon glyphicon-pencil"></span> New Message
                </a>
                
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

                $data = $employee->viewSelectedMsg($sender,$reciever,$_GET['date']);
                
                if($data!=NULL)
                {
                    $data=$data[0];
                    if($data['status']==0)
                    {
                       $employee->changeCustomerServices($data['c_id'].'*'.$data['mgr_id'].'*'.$data['date']);
                    }
                          if($_GET['type']==1){
                            $senderMail = $data['receiverMail'];
                            $receiverMail = $data['senderMail'];
                          }
                          else{
                            $senderMail = $data['senderMail'];
                            $receiverMail = $data['receiverMail'];
                          }

                    echo '<h4>subject: '.$data['subject'].'</h4>';
                    echo '<p>from:'.$sender.' &lt;'.$senderMail.'&gt; </p>';
                    echo '<p>to:'.$reciever.'  &lt;'.$receiverMail.'&gt; </p>';
                    echo '<hr style="width:70%; border:.5px dashed #ddd;">';
                    echo 
                        '  
                            <textarea style="width:80%; height:300px; margin:20px auto; resize:none;" readonly>Message :
                            '.$data['content'].'



                            '.$data['date'].'</textarea>
                        ';
                }
                
            }
        else
           {
               //search bar
              ?> 
               <div class="options col-xs-12">
                   <div class="col-md-offset-0 col-md-6 col-xs-12">
                       <button class="btn btn-default My_messages"
                        onclick="view_Supervisorinbox()">
                       Inbox</button>

                       <button class="btn btn-default send_messages" 
                        onclick="view_SuperviorSendMessages()">
                       Sent Messages</button>

                       <button class="btn btn-default send_messages"
                        onclick="view_servicesAccepted()">Accepted Services</button>

                       <button class="btn btn-default send_messages" 
                        onclick="view_unacceptedServices()">Non-activated Services</button>

                       <button class="btn btn-default send_messages" 
                        onclick="viewReport()">Reports</button>

                   </div>

                   <div class="mailSearchInp input-group col-md-offset-6 col-md-6 col-md-offset-0 col-xs-12">
                       <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                       <input type="text" class="form-control input-lg searchMailBox" placeholder="Search Mails" aria-describedby="basic-addon1" onkeyup="searchbox(this.value)">
                   </div>

               </div>
               <div class="card-body">
                    <div class="mailboxTable table-responsive">
                       <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead class="mailBox_header">
                               <tr>
                                   <th >Sender</th>
                                   <th >message</th>
                                   <th >Date</th>
                               </tr>
                           </thead>
                       <tbody class="mailbox">
                        <?php 
                        $m = new CustomerService();
                        $m->setKind(3);
               $data = $employee->readMessage($m,null,'All');
               if($data!=NULL)
               {
                   if(is_array($data))
                   {
                       foreach ($data as $value) 
                       {
                          echo ' 
                           <tr
                            onclick="viewSpecificManagerMessage('.$value['c_id'].' ,'
                           .$value['mgr_id'].',\''.$value['date'].'\')"
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
               {             echo 
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
        <div class="card-footer small text-muted">Updated at <?php $date = date('Y-m-d H:i'); echo $date; ?></div>
        </div>
    </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->     
</div>


</div>  <!-- end mailbox -->
<?php 
 include('includes/Footer.php'); 