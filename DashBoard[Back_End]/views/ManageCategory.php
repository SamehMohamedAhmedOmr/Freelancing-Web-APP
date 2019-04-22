<?php 
require 'includes/Header.php';
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
$Main_category = $employee->viewMainCategory(new Category());
$All_staff = $employee->view_all_staff(new Supervisor());
/*******************************************************************************/
/******************************* [Edit Category ] ******************************/
/*******************************************************************************/
if(isset($_GET['action']) && isset($_GET['cat_id']) && $_GET['action']=='view')
{
    // get specific category (the one we need to edit it ) & store it in array
    $data=$employee->getSelectedCategory($_GET['cat_id']);
    $data = $data[0];
    // store array of supervisor (who manage categoreis) & array of ( parent categories )
    $parent_cat_ids= array();
    $parent_cat_names= array();
    $parent_mgr_id = array();
    $parent_mgr_name = array();
    foreach ($Main_category as $value)
    {$parent_cat_ids[]=$value['cat_id'];$parent_cat_names[]=$value['name'];}
    foreach ($All_staff as $value)
    {$parent_mgr_id[]=$value['mgr_id'];$parent_mgr_name[]=$value['name'];}   

   ?>
<div id="Dashboard" class="tab_content container">
    <div id="showERROR">
    <?php
        // Display Add category's Errros
        if(isset($_SESSION['cat_errors']))
        {echo $_SESSION['cat_errors'];unset($_SESSION['cat_errors']);}
        // Display Add category Sucess Message
        if(isset($_SESSION['cat_sucess']))
        {echo $_SESSION['cat_sucess'];unset($_SESSION['cat_sucess']);}
    ?>
    </div>
    <div class="col-xs-offset-1 col-xs-10 formBox">
        <div class="boxHeader">
            <h4 class="text-center title">Edit Category</h4>
        </div>
        <div class="boxBody">
            <form action="view" method="GET" class="form-horizontal " style="margin-top: 30px;">
                <div class="inpGrp catLable">
                    <label >Category Name </label>
                    <input readonly id="nameField" type="text" class="form-control name" name="name" placeholder="Category Name"
                    value = "<?php echo $data["name"] ?>">
                </div>

                <div class="inpGrp catLable">
                    <label>Description</label>
                    <textarea readonly id="desField" class="form-control descripiton" rows="4" id="comment"
                    style="resize: none;" placeholder="Category Description" name="des"><?php echo $data["description"] ?></textarea>
                </div>

                <div id="visorField" class="inpGrp catLable">
                    <label >Supervisor </label>
                    <input readonly id="visorField" type="text" class="form-control name" name="name" placeholder="Supervisor Name"
                    value = "<?php echo $parent_mgr_name[array_search($data["mgr_id"], $parent_mgr_id)]; ?>">
                </div>

                <div  id="selectVisor" class=" hidden inpGrp catLable " id ="selectSupervisor">
                    <label for="select">Select Supervisor</label>
                    <select class="form-control " id="selectboxSuper" name="supervisor">
                        <option disabled value="No" >Please Select the Supervisor </option>;
                        <?php
                        foreach ($All_staff as $value) 
                        {
                            echo "<option ".(($data["mgr_id"] == $value['mgr_id'])?"selected":"") ." value=".$value['mgr_id']." name ='value'>".$value['name']."</option>";
                        }?> 
                    </select>
                </div> 

                <div class="inpGrp catLable">
                    <label >Ordering </label>
                    <input readonly id="orderingField" type="number" class="form-control name" name="order" placeholder="Category Importance" min="0" max="100" value = "<?php echo $data["ordering"]; ?>">
                </div>
            <?php
            if ($data["parent"]!=0) 
            {?>
                <div id="parentdiv" class="inpGrp catLable">
                   <label >Parent </label>
                   <input readonly id="parentField" type="text" class="form-control name" name="name" placeholder="Category Name"
                   value = "<?php echo $parent_cat_names[array_search($data["parent"], $parent_cat_ids)]; ?>">
                </div>

                <div class=" inpGrp catLable hidden" id ="selectParent">
                    <label for="select">Select Parent Category:</label>
                    <select class="form-control" id="selectboxCat" name="parent">
                        <option disabled value="No" >Please Select the Main-Category </option>
                        <?php
                        foreach ($Main_category as $value) 
                        {
                            echo "<option ".(($data['parent'] == $value['cat_id'])?"selected":"") ." value=".$value['cat_id']. " name ='value' >".$value['name']."</option>";
                        }
                        ?>
                    </select> 
                </div>

                <div class=" inpGrp catLable hidden checkbox" id ="change" style="padding: 0;">
                   <label style="font-size: 16px; cursor: default;">Change to Main Category</label>
                   <label>
                        <input id ="check" type="checkbox" class="form-control name" name="0"> 
                        <i class="helper"></i>
                   </label>
                </div>

            <?php
            }?>
                <div class=" inpGrp catLable hidden checkbox" id ="changeVisiblitydiv" style="padding: 0;">
                    <label style="font-size: 16px; cursor: default;">Change visiblabilty</label>
                    <label>
                        <input id ="changeVisiblity" type="checkbox"  name="changeVisiblity" data-id= '<?php echo $data["visible"]; ?>'> 
                        <i class="helper"></i>
                    </label>
                </div>
                <div class="boxFooter text-center title" id="insert" name = "<?php echo $data["parent"]; ?>" >
                    <input id="Edit" class="addBtn btn btn-default" type="submit" value="Edit" name = "<?php echo $data["cat_id"] ?>" 
                    onclick="return false;">
                </div>
            </form>
        </div>
    <?php
}
/*******************************************************************************/
/******************************* [View Category ] ******************************/
/*******************************************************************************/
else
{
    echo '<div style="padding:30px;font-family: Comic Sans MS;"> <h3 class="showResult text-center">Manage Category</h3><div>';        
      if ($Main_category) 
      {
          foreach ($Main_category as $cat) 
          {
              echo '<div class="catGrid col-lg-4 col-sm-6 col-xs-12" style="margin-top:45px;"> 
                    <div class="catitem" '.(($cat['visible']==0)?'style="opacity:.5;"':'').'>
                        <h3 class="text-center">'.$cat['name'] .' 
                            <a class="deleteMainCategory" data-id='.$cat['cat_id'].'
                               data-toggle="confirmation" data-title="Delete category !?" data-btn-ok-label="Delete " data-btn-ok-class="btn-danger" 
                               data-btn-cancel-label="Cancel" data-content="Are you sure you wanna to Delete category" data-btn-cancel-class="btn-info"  
                               data-on-confirm="deleteMainCategory"
                            >
                                <i class="fa fa-times pull-right main-times" aria-hidden="true"></i>
                            </a>
                            
                            <a href="?action=view&cat_id='.$cat['cat_id'].'"  class="editMainCategory" data-id="'.$cat['cat_id'].'" >
                              <i class="fa fa-pencil pull-right main-pencil" aria-hidden="true"></i>
                            </a>
                        </h3>
                            
                        <div class="catData">
                            <div class="catdes"><h5>category description </h5>
                                <div class="descbody">
                                '.(!empty($cat["description"])?$cat["description"]:'there is no Description').'             
                                </div>
                            </div>  
                            <div class="catorder"> order Number  
                                <div class="catordernum pull-right">'.$cat['ordering'].'</div>
                            </div>
                            <div class="catorder"> Supervisor 
                                <div class="catordernum pull-right">';
                                foreach ($All_staff as $value) {
                                    if($value['mgr_id']==$cat['mgr_id']){
                                        echo $value['name'];
                                        break;
                                    }
                                }
                            echo '</div>
                            </div>
                            <hr class="subHr">                                                   
                            <div class="subcategory">';
                        $subdate = $employee->viewsubCategory(new Category(),$cat['cat_id']);
                        if($subdate)
                        {   
                            echo '<p class="subHeader">sub Category</p>
                                 <ul class="sublist list-unstyled">';
                            foreach ($subdate as $sub) 
                            {
                                echo 
                                '<li class="subCategory">'
                                    . '<span>'.$sub['name'].'<span>
                                        <a class="deleteSubCategory"  data-id="'.$sub['cat_id'].'" 
                                            data-toggle="confirmation" data-title="Delete category !?" data-btn-ok-label="Delete " data-btn-ok-class="btn-danger" 
                                            data-btn-cancel-label="Cancel" data-content="Are you sure you wanna to Delete category" data-btn-cancel-class="btn-info"  
                                            data-on-confirm="deleteSubCategory"   
                                        >
                                            <i class="fa fa-trash pull-right sub-times" aria-hidden="true"></i>
                                        </a>                                    
                                        <a href="?action=view&cat_id='.$sub['cat_id'].'" class="editSubCategory" data-id="'.$sub['cat_id'].'" >
                                            <i class="fa fa-pencil pull-right sub-pencil" aria-hidden="true"></i>
                                        </a>
                                </li>';
                            }
                            echo '</ul>';
                        }
                        else{echo '<p class="subHeader" >there is no sub category</p>';}
                    echo '</div>
                </div>
            </div>
        </div>';
        }
    }
    echo '</div>';
}
 require 'includes/Footer.php'; 
 