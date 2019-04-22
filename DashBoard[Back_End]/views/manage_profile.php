<?php
require 'includes/Header.php';
 if($_SERVER['REQUEST_METHOD']=='POST')
{
    $password=$_POST['password'];
    $name=$_POST['name'];
    /********************validation**************************/
    $Error = array();
    //validate Name
    $oldimage =  $employee->getPhoto_name();
    $check = $employee->setName($name);
    if($check!=NULL)
    {$Errors[]=$check;}
    //validate password
    $check = $employee->setPassword($password);    
    if($check!=NULL)
    {$Errors[]=$check;}
    //validate image
    $img_flag=0;
    if(isset($_FILES['image']) &&!empty($_FILES['image']['name'])&&$_FILES['image']['error'] != UPLOAD_ERR_NO_FILE  )
    {
        $img_name= $_FILES['image']['name'];
        $image_tmp=$_FILES['image']['tmp_name'];         
        $img_type= $_FILES['image']['type'];
        $img_size=$_FILES['image']['size'];
        //validate image
        $check=$employee->setimg($img_name,$img_size,$employee->getE_mail());
        if($check!=NULL)
        {$Errors[]=$check;}
        else {$img_flag=1;}
    }
        
    if(empty($Errors))
    {
        $check_img = true;
        if($img_flag==1)
        {
            $path =  "..\images\uploads\\employee_photos\\".$employee->getPhoto_name();
            $check_img = move_uploaded_file($image_tmp,$path);
            if($check_img==false)
            {$_SESSION['addsupervisor_fail']='<p class="input_validate_error">failed to add employee<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';} 
        }
        if($check_img==true)
        {
            $check = $employee->MangeProfile($employee->db_connection);
            if($check==1)
            {
                if($oldimage!=$employee->getPhoto_name())
                {unlink('..\images\uploads\\employee_photos\\'.$oldimage);}
                $_SESSION['editprofile_sucess']='<p class="input_validate_sucess">your personal information updayed successfully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>';
                
                //prepare object to update session
                $employee->db_connection->disconnect();
                $_SESSION['Employee'] = serialize($employee);
            }
            else {$_SESSION['editprofile_fail']='<p class="input_validate_error">failed to update you personal information<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';} 
        }       
    }
    else
    {
        $_SESSION['editprofile_fail']='';
        foreach ($Errors as $value) 
        {$_SESSION['editprofile_fail'].='<p class="input_validate_error">'.$value.'<i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';}
    }   
    header("Location: manage_profile.php");
    exit();
}
?>

<div class="<?php if($employee->getGroupID()!= 0){    echo 'mngSupr';}else{echo 'mngAdmin'; }?>">
    
    <div style="padding-top:5px;">
            <?php
            //display errror & sucess messages if it's founexist
            if(isset($_SESSION['editprofile_sucess']))
            {echo $_SESSION['editprofile_sucess']; unset($_SESSION['editprofile_sucess']);}
            if(isset($_SESSION['editprofile_fail']) )
            {echo $_SESSION['editprofile_fail'];unset($_SESSION['editprofile_fail']);}
            ?>
        </div>

        <div class="formBox col-md-offset-2 col-md-8 col-xs-12">
            <div class="boxHeader">
                <h4 class="text-center title"><?php echo  $employee->getName(); ?>'s Profile</h4>
            </div>
            
             <div class="boxBody container-fluid text-center">
                <form class="form-horizontal" action="manage_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="leftSec col-sm-4">
                        <img id="autoload" src="../images/uploads/employee_photos/<?php echo $employee->getPhoto_name(); ?>" style="width:130px; height:120px;" class="img-thumbnail">
                        <div class="text-center btnSec" >
                            <label class="formBtn btn btn-md col-sm-8 col-sm-offset-2 change"  >
                                choose Image <input type="file"  name="image" style="display: none;" onchange="document.getElementById('autoload').src = window.URL.createObjectURL(this.files[0])">
                            </label>
                               <!--<button class="formBtn btn btn-md col-sm-8 col-sm-offset-2" >Update</button> -->
                               <br>
                               <?php
                                if($employee->getGroupID()!= 0)
                                {
                                ?>
                               <button type="button" id="editp" class="formBtn btn btn-md col-sm-8 col-sm-offset-2" onclick="edit();return false;">Edit</button>
                                    <input type="submit" id="submit_button" class="hidden formBtn btn btn-md col-sm-8 col-sm-offset-2">
                                <?php
                                }
                                else{
                                ?>
                                    <button type="button" id="editp" class="formBtn btn btn-md col-sm-8 col-sm-offset-2" onclick="edit(this);">Edit</button>
                                    <input type="submit" id="submit_button" class="hidden formBtn btn btn-md col-sm-8 col-sm-offset-2">
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                    <div class="rightSec col-sm-8">
                        <div class="form-group">
                            <label class="col-sm-5 control-label text-center" for="usr">Name :</label>
                            <div class="col-sm-7"><input type="text" name="name" id="name" value="<?php echo  $employee->getName(); ?>" name="name" readonly class="form-control colored-tooltip" data-toggle="tooltip" data-original-title="Unable to change!"data-placement="left"></div>
                        </div>
                    <br>
                    <div class="form-group">
                        <label class="col-sm-5 control-label" for="usr">Email :</label>
                        <div class="col-sm-7"><input type="text" name="mail"  value="<?php echo $employee->getE_mail(); ?>" readonly class="form-control colored-tooltip" data-toggle="tooltip" data-original-title="Unable to change!"data-placement="left"></div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-sm-5 control-label" for="usr">Password :</label>
                        <div class="col-sm-7"><input type="password" name="password" id="password" value="<?php echo $employee->getPassword(); ?>"Name="email" readonly class="form-control colored-tooltip" data-toggle="tooltip" title="Unable to change!"data-placement="left"></div>
                    </div>
                    <br>
                    
                     
        <?php
            if($employee->getGroupID()!= 0)
            {
                ?>
                    <div class="form-group">
                        <label class="col-sm-5 control-label" for="usr">Date of Hiring :</label>
                        <div class="col-sm-7"><input type="text" value="<?php echo $employee->getHireDate(); ?>" readonly  class="form-control colored-tooltip" data-toggle="tooltip" title="Unable to change!"data-placement="left"></div>
                    </div>
                    <br>
                    
                    <div class="form-group">
                        <label class="col-sm-5 control-label" for="usr">Number of Hours :</label>
                        <div class="col-sm-7"><input type="text" id="hours" value="<?php echo $employee->getHours(); ?> Hours per day"name="hours" readonly class="form-control "></div>
                    </div>
                    <br>
                    
                    <div class="form-group">
                        <label class="col-sm-5 control-label" for="usr">Salary :</label>
                        <div class="col-sm-7"><input type="text" id="salary" value="<?php echo $employee->getSalary(); ?> $ for each Hour"name="salary" readonly class="form-control"></div>
                     </div><br>
                   
        
               <?php
            }
           
        ?>
        
        
                    </div>
                    </form>
             </div>
        </div>
</div>

<?php
 require 'includes/Footer.php'; 