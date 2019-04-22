<?php 
require 'includes/Header.php';
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
//Display Specific service
if(isset($_GET['action']) && isset($_GET['service_id']) && $_GET['action']=='view')
{
    $service = new Services();
    $check=$service->setService_id($_GET['service_id'],$employee->db_connection);
    if (empty($check)) {
      //  echo "Yes I'm empty";
         $client = $employee->showSelectedServices($service);
         $Images = $service->getImages($employee->db_connection);
?>
    <!--start to design -->
    <div class="container col-xs-12">     
        <div class="specific_service_view row">
            <div name="Description" class="panel panel-default service_des_div col-sm-6">                
                <div name ="Service-title" class="service_title_div">
                    <h2 class="well"> <?php echo $service->getService_name(); ?> </h2>
                </div> <br> <br>
                <div class="panel panel_content">
                    <p class="panel-body description_content"> <?php echo $service->getDescription(); ?></p>
                    <?php 
                      if($Images){
                    ?>
                        <div style="width: 100%;">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <?php 
                                        for ($i=0; $i <count($Images) ; $i++) { 
                                            echo '<li data-target="#myCarousel" data-slide-to="'.$i.'">
                                                  </li>';
                                    }?>
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <?php
                                    $counter=0;
                                    foreach ($Images as $value) 
                                    {
                                        echo '<div class="item '.(($counter==0)?'active':'').'" style="width:100%;">
                                                <img src="../../FrontEnd/Images/service_images/'.$value['image'].'" 
                                                     style=" height:270px;">
                                              </div>';
                                        $counter++; 
                                    }?>
                                </div>
                                <!-- Left and right controls -->
                                <?PHP if($counter>1){ ?>
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                  <span class="glyphicon glyphicon-chevron-left"></span>
                                  <span class="sr-only">Previous</span>
                                </a>

                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                  <span class="glyphicon glyphicon-chevron-right"></span>
                                  <span class="sr-only">Next</span>
                                </a>
                            <?php }?>
                            </div>
                        </div>
                    <?php } ?> <!-- end of If condition -->
                </div> 
            </div>            
            <!-- INFORMATION DIV !-->
            <div class="panel-default service_info col-sm-6">
                <h5 class="panel-heading fa INFORMATION"><span class="fa fa-info INFORMATION_Style"> Information</span> <br></br>
                    <div name="Type" class="panel panel-default  service_Type_div ">
                        <h5 class="fa fa-briefcase panel-heading Type_date_panal"> <span class="infotype">Category:</span>
                        <span> 
                            <a href="#"> <?php echo $service->getType($employee->db_connection); ?> </a> </span> 
                        </h5>
                    </div>
                    
                    <div name="Service_date" class="panel panel-default service_date_div">
                        <h5 class="fa fa-calendar panel-heading Service_date_panal"> <span class="infotype">Create Date:</span> 
                        <span> <?php echo $service->getService_date(); ?> </span>
                        </h5>
                    </div>

                    <div name="Deliver Date" class="panel panel-default service_deliver_div">
                    <h5 class="fa fa-calendar-o panel-heading deliver_date_panal"> <span class="infotype">Deliver Date:</span> 
                    <span> <?php echo $service->getDeliver_date(); ?> </span> 
                    </h5>
                    </div>

                    <div name="price" class="panel panel-default service_price_div">
                        <h5 class="fa fa-usd panel-heading price_panal"> <span class="infotype">Price:</span> 
                        <span>$ <?php echo $service->getPrice();  ?> </span>
                        </h5>
                    </div>
                    
                    <div name="view" class="panel panel-default service_view_div">
                        <h5 class="fa fa-eye panel-heading view_panal"> <span class="infotype">views:</span> 
                        <span> <?php echo $service->getService_view(); ?> </span> 
                        </h5>
                    </div>
                    
                    <div name="Tags" class="panel panel-default service_tag_div">
                        <h5 class="fa fa-tags panel-heading tag_panal"> <span class="infotype">Tags:</span>   <br><br>
                            <?php 
                            $tags = explode("-",$service->getTags());
                            $i=0;
                            foreach ($tags as $value) {
                                echo '<span> <a href="#" class="ServTeg">'
                                        .$value.'</a>'
                                        .(($i==count($tags)-1)?' ':', ').
                                     '</span>';
                                $i++;
                            }
                            ?>
                        </h5>
                    </div>

                    <div name="Type" class="panel panel-default service_owner_div">
                        <h5 class="fa fa-user panel-heading Type_date_panal"> <span class="infotype">Owner:</span>
                            <?php  $data = $service->getOwner($employee->db_connection,$client);
                                $Owner = $data[0];
                                echo '<span><a href="#" class="ServOwner">'.$Owner['c_name'].'</a></span>';
                                if($Owner['image'])
                                {
                                    echo '<br><br>';
                                    if(preg_match('/https/',$Owner['image']))
                                    {
                                         echo '<img  class="img-thumbnail" src="'.$Owner['image'].'" style="height:110px; width:100px;">';
                                    }else
                                    {
                                        echo '<img  class="img-thumbnail" src="../../FrontEnd/Images/client_images/'.$Owner['image'].'"  
                                            style="height:110px; width:100px;">';
                                    }                                        
                                 }
                            ?>
                        </h5>
                    </div>
                    <div class="clearfix"></div>
                </h5>    
            </div>
        </div>
    </div>
<?php  
    }
}
// display All Services
else
{ 
     $type="All";
    if(isset($_GET['type']) && ($_GET['type']=="Activated" || $_GET['type']=="deActivated"))
    {$type=$_GET['type'];}
    
    $service=new Services();
    $data = $employee->viewServices($service,$employee->getID() ,0, $type); 
    ?>
    <div id="Dashboard" class="tab_content container">
        
        <div class="tableContainer col-lg-12"> 
            
            
            <div class="servSearchInp input-group col-md-offset-6 col-md-6 col-md-offset-0 col-xs-12">
                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control service_search input-sm" placeholder="Search for service" aria-describedby="basic-addon1">
            </div>
            <div class="pull-right" style="margin-bottom: 5px;">
                <a class="btn btn-default" href="?type=All">All</a>
                <a class="btn btn-default" href="?type=Activated">Activated Services</a>
                <a class="btn btn-default" href="?type=deActivated">deActivated Services</a>
            </div>
            <table class="table table-responsive table-hover service_table">
                <thead>
                  <tr style="background-color:red;">
                    <th>Name</th>
                    <th>Category</th>
                    <th>Owner</th>
                    <th>Date of Service</th>
                    <th>price</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Browse</th>
                    <th>delete</th>
                    <th>Save</th>
                  </tr>             
                </thead>
                <tbody>
                <?php
                if($data!=0)
                {
                    foreach ($data as $value) {
                        ?>
                        <tr class="text-center <?php echo (($value['status']==1)?"service_Activated":"service_deactivated"); ?> ">
                            <td><?php echo $value['name'] ;?></td>
                            <td><?php echo$value['Category']; ?></td>
                            <td><?php echo$value['Owner']; ?></td>
                            <td><?php echo$value['s_date']; ?></td>
                            <td><?php echo $value['price']; ?> $</td>
                            <td><?php echo $value['view']; ?> <i class="fa fa-eye"></i></td>

                            <td>
                                <select id="serviceStatus" onchange="opensaveButton(this)" class="form-control input-sm serStatus">
                                    <option <?php echo (($value['status']==1)?"selected":""); ?> >Activated</option>
                                    <option <?php echo (($value['status']==0)?"selected":""); ?> >Non Activated</option>
                                </select>
                            </td>


                           <td>
                               <a  class="servBtn btn btn-sm btn-default" href="?action=view&service_id=<?php echo $value['s_id']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                           </td>

                            <td>
                                <button class="service_remove btn btn-sm btn-danger" onclick="removeService(<?php echo $value['s_id'] ;?> , this)">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-default saveButton" disabled="disabled" onclick="updateStatus(<?php echo $value['s_id'] ;?> , this)">
                                   <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>                            
                        <?php
                    } 
                }
                else
                {
                    echo 'there is No Data';
                }     
                ?>
            </tbody>     
        </table>
    <div>
</div>
<?php 
}
 require 'includes/Footer.php'; 