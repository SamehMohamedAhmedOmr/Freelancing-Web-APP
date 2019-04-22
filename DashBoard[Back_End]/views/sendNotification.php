<?php 
include('includes/Header.php');
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST')
        {
    if(isset($_POST['subject']) && isset($_POST['content'])){

        $error;
        $notification = new Notification();
        $notification->setDate();

        // validate notification
        $check= $notification->setMessage($_POST['content']);
        if($check!== true){$error[] = $check;}
        //validate subject
        $check= $notification->setSubject($_POST['subject']);
        if($check!== true){$error[] = $check;}

        // if there is validation error dont save message and display errors , else save message 
        if(!isset($error))
                {
           $check = $employee->notifyObserver($notification);
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
        header("Location: sendNotification.php");
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
                            Notification <div class="tablinks" style="float:right; margin-right:10px;"></div>                    
               </div>

                        <div class="card-body" style="">

                            <div class="form-group">
                                <label for="subject">Subject : </label>
                                <input type="text" class="form-control" placeholder="Notification Subject" name="subject">
                            </div>
                                               
                            <div class="form-group">
                                <label for="subject">Content : </label>
                                <textarea name="content" type="text" class="form-control" placeholder="Enter your Notification" style="resize:none;"></textarea>
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