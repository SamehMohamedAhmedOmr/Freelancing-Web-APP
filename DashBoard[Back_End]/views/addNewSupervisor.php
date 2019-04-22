<?php 
require 'includes/Header.php';
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST')
{
   if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['pass']))
   {
        // store data
        $mail = $_POST['mail'];
        $pass = $_POST['pass'];
        $name = $_POST['name']; 
        $hours=$_POST['hours'];
        $salary=$_POST['salary'];
        $date=$_POST['date'];
        $supervisoe = new Supervisor();
        $error =[];
        $check = null;
        $img_flag=0;
        if(isset($_FILES['image'])&&!empty($_FILES['image']['name'])&&$_FILES['image']['error'] != UPLOAD_ERR_NO_FILE )
        {
            $img_name= $_FILES['image']['name'];
            $image_tmp=$_FILES['image']['tmp_name'];         
            $img_type= $_FILES['image']['type'];
            $img_size=$_FILES['image']['size'];
            //validate image
            $check=$supervisoe->setimg($img_name,$img_size,$mail);
            echo $supervisoe->getPhoto_name();
            if($check!=NULL)
            {$error[]=$check;}
            else {$img_flag=1;}
        }
       
        //validate password
        $check=$supervisoe->setPassword($pass); 
        if($check!=NULL)
        {$error[]=$check;}
        //validate E-mail
        $check=$supervisoe->setE_mail($mail);
        if($check!=NULL)
        {$error[]=$check;}
        //validate Name 
        $check=$supervisoe->setName($name); 
        if($check!=NULL)
        {$error[]=$check;}
        //validate Hours
        $check=$supervisoe->setHours($hours);
        if($check!=NULL)
        {$error[]=$check;}
        //validate salary
        $check=$supervisoe->setSalary($salary);
        if($check!=NULL)
        {$error[]=$check;}
        //validate HireDate
         $check=$supervisoe->setHireDate($date);
         if($check!=NULL)
         {$error[]=$check;}
 
 /************Add Supervisor function *******************/
        if(empty($error))
        {
            if($img_flag==1)
            {
                move_uploaded_file($image_tmp, "../images/uploads/employee_photos/".$supervisoe->getPhoto_name());
            }
            $check = $employee->addSupervisor($supervisoe , $img_flag);
            if($check==1){$_SESSION['addsupervisor_sucess']='<p class="input_validate_sucess">supervisor added successfully <i class="fa fa-check-square icon" aria-hidden="true"></i></p>';}
            else {$_SESSION['addsupervisor_fail']='<p class="input_validate_error">failed to add employee <i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';} 
        }
        else
        {   
                $_SESSION['addsupervisor_fail']='';
                foreach ($error as $value) 
                {$_SESSION['addsupervisor_fail'].=
                    '<p class="input_validate_error">'.$value.
                        '<i class="fa fa-times-circle icon" aria-hidden="true"></i>'
                  . '</p>';}
        }  
            header("Location: addNewSupervisor.php");
            exit();
    }
 }

?>
<div id="Dashboard" class="tab_content container">
    <?php
        if(isset($_SESSION['addsupervisor_fail']))
        {echo $_SESSION['addsupervisor_fail']; unset($_SESSION['addsupervisor_fail']);}
        
        if(isset($_SESSION['addsupervisor_sucess']) )
        {echo $_SESSION['addsupervisor_sucess'];unset($_SESSION['addsupervisor_sucess']);}
    ?>
    <div class="col-xs-offset-1 col-xs-10 formBox">
            <div class="boxHeader">
                <h4 class="text-center title">Add New Supervisor</h4>
            </div>
            <div class="boxBody">
                <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Name">
                        </div>

                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            <input type="mail" class="form-control" name="mail" placeholder="Email">
                        </div>

                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" class="form-control" name="pass" placeholder="Password">
                        </div>

                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            <input type="number" onkeypress="return event.charCode >= 48" min="0" class="form-control" name="hours" placeholder="Hours of work">
                        </div>


                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                            <input step="0.5" type="number" onkeypress="return event.charCode >= 48" min="0" class="form-control" name="salary" placeholder="Salary per hour ($)">
                        </div>
                        
                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="date" class="form-control" data-toggle="tooltip" name="date" title="Date of Hiring">
                        </div>
                    
                        <div class="inpGrp input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                            <label class="btn btn-default btn-block btn-sm btn-file">
                                Supervisor Image<i class="fa fa-file-image-o" aria-hidden="true"></i>
                                <input type="file" class="form-control" style="display: none;" name="image" data-toggle="tooltip" title="Personal Image">
                            </label>
                        </div>
                    
                        <div class="boxFooter text-center title">
                            <button type="submit" class="addBtn btn btn-default">Add</button>
                        </div>
                          
                    </form>
            </div>
            
        </div>
</div>
<?php 
 require 'includes/Footer.php'; 
