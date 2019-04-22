<?php 
require 'includes/Header.php';
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
$Main_category = $employee->viewMainCategory(new Category());
$All_staff = $employee->view_all_staff(new Supervisor());   
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST['AddCategory']))
    {   
        $categoy = new Category(); 
        // check Errors , create array to store Errros
        $Errors = array();   
        // validate Name
        $check = $categoy->setCat_Name($_POST['name']);
        if($check!=null){$Errors[]=$check;}
        // validate category
        $check =$categoy->setCat_Description($_POST['descripiton']);
        if($check!=null){$Errors[]=$check;}
        // validate supervisor 
        $check = ((!isset($_POST['supervisor']))?"pls choose supervisor to manage category":null);
        if($check!=null){$Errors[]=$check;}
        //validate Order        
        $check=$categoy->setOrdering($_POST['order']);
        if($check!=null){$Errors[]=$check;}
        //validate visible
        $categoy->setVisible(1);
        // validte parent
        if(!isset($_POST['optradio']))
        { $Errors[]='pls select the type of category'; }
        else
        {
            if($_POST['optradio'] == 'Sub-Category') 
            {
                if (isset($_POST['parent'])) 
                {
                    $parent = $_POST['parent'];
                    $check=$categoy->setParent($_POST['parent'],$employee->db_connection);
                    if($check!=null){$Errors[]=$check;}
                }
                else{$Errors[]='category\'s parent can\'t be empty ';}
            }
            else
            { $categoy->setParent(0,0,1); }
        }
        if(empty($Errors))
        {
            $check = $employee->addCategory($_POST['supervisor'], $categoy); 
            if($check==0)
            {$_SESSION['cat_errors']='<p class="category_error">Failed to Added Category pls try agagin later <i class="fa fa-times-circle-o" aria-hidden="true"></i></p>';}
            else {$_SESSION['cat_sucess']='<p class="input_validate_sucess">Added category sucessfully <i class="fa fa-check-square icon" aria-hidden="true"></i> </p>'; }
        }
        else
        {
            $_SESSION['cat_errors']='';
            foreach ($Errors as $value){$_SESSION['cat_errors'].='<p class="input_validate_error">'.$value.'<i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';}
        }
        header("Location: addNewCategory.php");
        exit();
   }
}

?>

<div id="Dashboard" class="tab_content container">
    <?php
    // Display Add category's Errros
    if(isset($_SESSION['cat_errors']))
    {echo $_SESSION['cat_errors'];unset($_SESSION['cat_errors']);}
    // Display Add category Sucess Message
    if(isset($_SESSION['cat_sucess']))
    {echo $_SESSION['cat_sucess'];unset($_SESSION['cat_sucess']);}    
    ?>
    <div class="col-xs-offset-1 col-xs-10 formBox">
        <div class="boxHeader">
            <h4 class="text-center title">Add new Category</h4>
        </div>
        <div class="boxBody">
            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="col-xs-offset-3 col-xs-6" style="margin-top: 30px;">
                <div class="inpGrp catLable">
                    <label>Category Name </label>
                    <input type="text" class="form-control name" name="name" placeholder="Category Name" autofocus required>
                </div>
                <div class="inpGrp catLable">
                    <label>Description</label>
                    <textarea class="form-control descripiton" rows="4" id="comment"
                              name="descripiton" style="resize: none;" placeholder="Category Description"></textarea>
                </div>
                <div class="inpGrp catLable" id ="selectSupervisor">
                    <label for="select">Select Supervisor</label>
                    <select class="form-control" id="selectbox" name="supervisor">
                        <option disabled="disabled" selected value="No" >Please Select the Supervisor </option>
                      <?php 
                        foreach ($All_staff as $value) 
                        { echo "<option value=".$value['mgr_id']." name ='value'>".$value['name']."</option>";}
                      ?> 
                    </select>
                </div> 
                <div class="inpGrp catLable">
                    <label >Ordering </label>
                    <input type="number" class="form-control name" name="order" placeholder="Category Importance">
                </div>

                <div class="inpGrp catLable">
                    <label class="radio-inline"><input id="Main-Category" type="radio" name="optradio" value="Main-Category " onclick="hide('#SelectedForm')"><b>Main-Category</b></label>
                    <label class="radio-inline"><input id="Sub-Category" type="radio" name="optradio" value="Sub-Category" onclick="show('#SelectedForm')"><b>Sub-Category</b></label>
                </div>
                <!-- <div class=" hidden form-group"> -->
                <div class="hidden inpGrp catLable" id ="SelectedForm">
                    <label for="select">Select Parent Category:</label>
                    <select class="form-control" id="selectbox" name="parent">
                        <option disabled selected value="No" >Please Select the Main-Category </option>
                    <?php 
                        foreach ($Main_category as $value) 
                        {echo "<option value=".$value['cat_id']. " name ='value'>".$value['name']."</option>";} 
                    ?>
                    </select>
                </div>
                <div class="boxFooter text-center title">
                    <input class="addBtn btn btn-default" name="AddCategory" type="submit" value="Add Category"/>
                </div>
            </form>
        </div>
<?php
 require 'includes/Footer.php'; 