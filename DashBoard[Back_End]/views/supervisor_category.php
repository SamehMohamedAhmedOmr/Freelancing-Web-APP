<?php
require 'includes/Header.php';
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
$mycategory = $employee->view_myCategory(new Category());
?>
<div id="Dashboard" class="tab_content container">
    <div class="supervisor_cat">
        <h3 class="text-center text-danger" style="margin-bottom: 20px;">Your Category</h3>
            <div class="panel-group" id="" role="tablist" aria-multiselectable="true">
                
                <?php
                $count=0;
                foreach ($mycategory as $cat) 
                {
                    echo 
                    '<div class="panel panel-danger" >
                        <div class="panel-heading" role="tab" id="'.$count.'">
                            <h4 class="panel-title">
                              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#panel'.$count.'" aria-expanded="true" aria-controls="panel'.$count.'">
                                  <b class="category_Name"> '.$cat['name'].'</b>
                              </a>
                            </h4>
                        </div>
                        
                        <div id="panel'.$count.'" class="panel-collapse collapse '.(($count==0)?"in":"").'" role="tabpanel" aria-labelledby="headingOne" style="width: 90%;">
                            <div class="panel-body">
                                <!-- description-->
                                <div class="category_description">
                                    <textarea class="form-group form-control" style="width: 100%; height: 120px; resize: none;" readonly>  category Description :  
                                        '.$cat['description'].'
                                    </textarea>
                                    <span class="cat_order">order ['.$cat['ordering'].']</span>
                                </div>

                                <!-- status & type-->
                                    <div class="col-lg-6" style="padding-left: 0;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Status</span>
                                            <input type="text" class="form-control" value="'.(($cat['parent']!=Null)?"Parent category":"sub category").'">
                                      </div>
                                    </div>

                                    <div class="col-lg-6" style="padding-right: 0;">
                                      <div class="input-group">
                                        <span class="input-group-addon">Visibility</span>
                                        <input type="text" class="form-control" value="'.(($cat['visible']==1)?"Avilabel to Clients":"Not Avilabel to Clients").'">
                                      </div>
                                    </div> 

                                <div class="row">
                                    <div class="col-lg-12" style="padding: 0; margin-top: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">Number of services</span>
                                            <input type="text" class="form-control" value="'.$cat['number_of_services'].'">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>';
                    $count++;
                }                
            ?>    
    </div>
</div>
<?php
 require 'includes/Footer.php'; 
