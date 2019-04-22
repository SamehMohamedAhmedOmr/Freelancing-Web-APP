<?php session_start(); ?>
<html>
<body>
<?php
require '../config/includes.php';
$employee = unserialize($_SESSION['Employee']);
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $salary=$_POST['salary'];
    $hours=$_POST['hours'];
    $id=$_POST['ID'];
    $email = $_POST['email'];
    $supervisor = new Supervisor();
    
    //validation
    $Errors= [];
    //validate hours
    $check = $supervisor->setHours($hours);
    if($check!=NULL){$Error[]='pls enter valid hour';}
    // validate salary
    $check = $supervisor->setSalary($salary);
    if($check!=NULL){$Error[]='pls enter valid salary';}    
    $supervisor->setID($id);
    if(empty($Error))
    {
        //$supervisoe->updateSupervisor($supervisoe,$mail);
        $check_update = $employee->updateSupervisor($supervisor);    
        if($check_update==1)
        {$_SESSION['updated_sucess']= "<p class='input_validate_sucess'>employee account has updated sucessfully<i class='fa fa-check-square icon' aria-hidden='true'></i></p>";}
        else {$_SESSION['updated_fail']='<p class="input_validate_error">Error pls tray again later<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';}
    }
     else
    {
        $_SESSION['updated_fail']='';
        foreach ($Error as $value) {$_SESSION['updated_fail'].='<p class="input_validate_error">'.$value.'<i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';}
    }  

    header("Location: manageSupervisor.php?action=view&emp_id=".$supervisor->getID()."");
    exit();
}
?>
</body>
</html>