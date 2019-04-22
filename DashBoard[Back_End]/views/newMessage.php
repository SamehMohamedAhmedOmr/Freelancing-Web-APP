<?php 
include('includes/Header.php');
if($_SERVER['REQUEST_METHOD']=='POST')
        {
    if(isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['message']))
            {
        $error;
        $message = new Manager_Msg();
        $message->setDate();
        $message->setStatus(0);
        // validate message
        
        if($_POST['to']==$employee->getE_mail())
        {$error[] = 'you can\'t send message to yourself (fucking idiot) ' ;}
        $check= $message->setMessage($_POST['message']);
        if($check!== true){$error[] = $check;}
        //validate subject
        $check= $message->setSubject($_POST['subject']);
        if($check!== true){$error[] = $check;}
        //validate sender of message
        $check = $message->setTo($_POST['to'] , $employee->db_connection);
        if($check!== true){$error[] = $check;}
        // if there is validation error dont save message and display errors , else save message 
        if(!isset($error))
                {
           $check = $employee->sendMessage($message);
           if($check==1)
           {$_SESSION['msg_status']='<p class="input_validate_sucess">your message has been sent sucesfully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>';}
           else{$_SESSION['msg_status']='<p class="input_validate_error">oops ! pls try send message again <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';}
        }
        else
        {
             $_SESSION['message_error']='';
            foreach ($error as $value) {
                $_SESSION['message_error'].='<p class="input_validate_error">'.$value.' <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }
        }
        header("Location: newMessage.php");
        exit();
    }
}

?>
<div id="Newmsg" class="tab_content container">
    <div class="content-wrapper">
            <?php        
            if(isset($_SESSION['message_error']))
            {
            echo $_SESSION['message_error'];
            unset($_SESSION['message_error']);
            }
            if(isset($_SESSION['msg_status']))
            {
            echo $_SESSION['msg_status'];
            unset($_SESSION['msg_status']);
        }
        ?>
        <div class="container-fluid">
              <!-- Example DataTables Card-->
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
                <div class="card mb-3">
                        <div class="card-header">
                            New Message<div class="tablinks" style="float:right; margin-right:10px;"></div>                    
               </div>

                        <div class="card-body" style="">
                            <div class="form-group" style="position: relative;">
                                <label for="receiver">To : </label>
                                <input type="text" class="form-control" id="Email" placeholder="Email" name="to" value="" onkeyup="getManagerList(this.value)">
                                    <div class="email_result hidden">
                                        
                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject : </label>
                                <input type="text" class="form-control" placeholder="Message Subject" name="subject">
            </div>
                    
                            <div class="sep"><hr role="separator" class="divider"></hr></div>
                           
                            <div class="form-group">
                                <label for="subject">Message : </label>
                                <textarea name="message" type="text" class="form-control" placeholder="Enter your Message" style="resize:none;"></textarea>
                            </div>
                        </div>
                        
                        <div class="card-footer small text-muted">
                            <div>
                                    <button type="submit" class="btn btn-default submitmsg">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>Send
                                    </button>
    </div>
        </div>           
                </div>
            </form>
        </div>
      </div>
  </div>

<?php 
 include('includes/Footer.php'); 