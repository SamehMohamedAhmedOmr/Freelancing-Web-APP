<?php 
include('includes/Header.php');
?>
<div id="Mail" class="tab_content container"> <!-- start mailbox -->
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
              <i class="fa fa-table"></i>MailBox
              <a href="../views/newMessage.php" class="btn btn-primary btn-success">
                  <span class="glyphicon glyphicon-pencil"></span> New Message
              </a>
        </div>
        <?php
        if(isset($_GET['reciever']) && isset($_GET['sender']) && isset($_GET['date']))
        {
            $data = $employee->viewSpecificMessage($_GET['sender'],$_GET['reciever'],$_GET['date']);
            if($data!=NULL)
            {
                $data=$data[0];
                if($data['status']==0)
                {
                   $employee->changeMessageStatus($data['mgr_sender'].'*'.$data['mgr_reciever'].'*'.$data['date']);
                }?>
          <div>
                <div class="col-xs-10 col-xs-offset-1" style="padding-left: 0;">
                <img class="senderImg" src="../images/uploads/employee_photos/<?php echo $employee->getPhoto_name(); ?>">
                <div class="msgheader msgInfo">
                    <span><h4>subject: <small> <?php echo $data['subject'] ?> </small></h4></span>
                    <span>from: <small> <?php echo $data['senderMail'] ?> &emsp;&emsp;</small></span>
                    <span>to: <small> <?php echo $data['receiverMail']?> </small> </span><br/>
                    <span style="position: absolute;margin-top: 10px;"><small> <?php echo $data['date']?> </small> </span>
                </div>
                <!-- <hr style="width:70%; border:.5px dashed #ddd;"> -->
                <textarea class="msgContent col-xs-12" readonly><?php echo $data['message'];?></textarea>

            <?php        
            }
        }
        else
        {
        ?>
                
           <div class="options col-xs-12">
               <div class="col-md-offset-0 col-md-6 col-xs-12">
                   <button class="btn btn-default My_messages" onclick="view_inbox()">Inbox</button>
                   <button class="btn btn-default send_messages"  onclick="view_sendMessages()">Sent Messages</button>
               </div>

                <div class="mailSearchInp input-group col-md-offset-6 col-md-6 col-md-offset-0 col-xs-12">
                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control input-lg searchMailBox" placeholder="Search Mails" aria-describedby="basic-addon1" onkeyup="searchMailBox(this.value)" data-type="reciever" >
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
               $data = $employee->readMessage(new Manager_Msg() ,null,'All');
               if($data!=NULL)
               {
                   if(is_array($data))
                   {
                       foreach ($data as $value) 
                       {
                           
                           echo ' 
                           <tr onclick="viewSpecificMessage('.$value['mgr_sender'].' ,'.$value['mgr_reciever'].',\''.$value['date'].'\')"
                               '.(($value['status']==0)?"class=\"unread\" ":"").' class="messageBoxhover">
                             <td style="width:10%;" class="sender" >'.$value['senderName'].'</td>
                             <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['message'].'</td>
                             <td style="width:15%" class="date">'.$value['date'].'</td>
                           </tr>
                        '; 
                        
                       }                            
                   }
               }
               else
               { echo 'no msg yet';}
           } 
        ?>
            </tbody>
        </table>
    </div>
        </div>           
                <div class="card-footer small text-muted " >Updated at <?php $date = date('Y-m-d H:i'); echo $date; ?></div>
                </div>
        </div>
      </div>
  </div>

  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->     


</div>  <!-- end mailbox -->
<?php 
 include('includes/Footer.php'); 