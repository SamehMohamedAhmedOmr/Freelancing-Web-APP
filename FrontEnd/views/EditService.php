<?php 
require './includes/header.php';
if(isset($_SESSION["Client"]))
{
    $client = unserialize($_SESSION["Client"]);
}
else {
  header("Location: Home.php");
  exit();
}
if(isset($_GET['ser_id']) && intval($_GET['ser_id']))
{
    $service = new Services();
    $check=$service->setService_id($_GET['ser_id'],$client->db_connection);
    $stars = $service->getServiceStars($client->db_connection, $service->getService_id());
    $e = new Estimate();
    $estimates = $client->showServiceEstimates($e,$service);
    
    if (empty($check)) 
    {
         $serviceOwner = $client->showSelectedServices($service);
         
         $Owner = $service->getOwner($client->db_connection, $serviceOwner);
         $Owner=$Owner[0];
         $Images = $service->getImages($client->db_connection);
         
    }
    else
    {
        header("Location: Home.php");
        exit();
    }
}
else
{
    header("Location: Home.php");
    exit();
}
?>
<script>document.title="<?php echo $service->getService_name(); ?>";</script>
 <section>
    <?php 
    if(isset($_SESSION['msg_status'])){
    echo $_SESSION['msg_status'];
    unset($_SESSION['msg_status']);
    }
    elseif(isset($_SESSION['message_error'])){
    echo $_SESSION['message_error'];
    unset($_SESSION['message_error']);
    }
    ?>
    <div class="container">
        <div class="row">
            <!-- Modal -->
              <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <div class="contact">
                            <h2 >Contact with Service Provider</h2>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <textarea id = "messageData" name="messageData" type="text" class="form-control"  placeholder="Enter your Message" style="resize:none; height: 100px; margin-bottom: 5px;"></textarea>
                        </div>   
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-info btn-lg pull-right" 
                            onclick="contactwithowner(<?php echo $serviceOwner->getID(); ?>,
                            <?php echo $service->getService_id(); ?> )">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                 Send
                            </button>
                        </div>
                    </div>

                  </div>
                </div>
              </div>
            <!--Header of page -->
            <div class="ThirdSectionHeader viewCategorySection" id="USN">
                <h2 style="margin: 20px auto 50px auto;" ><?php echo $service->getService_name(); ?></h2>
            </div>
            <!-- Start Service Container -->
            <div class="serviceContainer col-xs-9">
                <!--Start Service Header -->
                <h2 class="text-left serviceName" id="SN"><?php echo $service->getService_name(); ?></h2>
                <!--End Service Header -->

                <!--Start Cursor -->
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <?php 
                            for ($i=0; $i <count($Images) ; $i++)  
                            { echo '<li data-target="#myCarousel" data-slide-to="'.$i.'"></li>';}
                        ?>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <?php
                        $counter=0;
                        foreach ($Images as $value) 
                        {
                            echo '<div class="item '.(($counter==0)?'active':'').'" style="width:100%;">
                                       <img src="../Images/service_images/'.$value['image'].'" class="serviceImage">
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
                <!--End Cursor -->
                
                <!-- Start service description -->
                <div class="serviceDesc"  id="updateDes">
                    <h3>Service Description:</h3>
                    <p><?php echo $service->getDescription(); ?></p>
                </div>
                <!-- End service description -->
                
                
                <!-- Start tags-->
                <div class="tags" id="updateTags">
                    <h3>Service Tags:</h3>
                    <?php 
                        $tags = explode("-",$service->getTags());
                        $i=0;
                        echo '<ul>';
                        foreach ($tags as $value) {
                            echo '<li>'
                                    .$value.
                                 '</li>';
                            $i++;
                        }
                        echo '</ul>';
                    ?>
                </div>
                <!-- End Tags -->
                <br>
                <hr style="border-width: 1px;">
                <br>
                <div>
                    <button  id="editInfoBtn" class="btn btn-info col-xs-3" type="button" name="editInfo" data-toggle="modal" data-target="#edit_modal">
                        Edit &nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                </div>
                
                <!-- Service options-->
                <!--
                <div class="service_payment">
                    <h3 class="text-center">Payment Methods</h3><br>
                    <button class="btn btn-success col-xs-3">Shopping Cart <i class="fa fa-shopping-cart"></i></button>
                    <div class="col-xs-1"></div>
                    <button class="btn btn-primary col-xs-3">Pay by credit card <i class="fa fa-credit-card"></i></button>
                    <div class="col-xs-1"></div>
                    <button class="btn btn-info col-xs-3">Pay by PayPal <i class="fa fa-paypal"></i> </button>
                </div>
                -->
                <!-- End Service Options-->
            </div>
            
            
            <!-- Start Service Estimates Div -->
            <div class="col-xs-3">
                <div class="serviceEstimate">
                    <h4 class="text-center" style="display: inherit;">
                        <!-- start service stars -->
                        <div class="starsEstimate">
                             <ul class="stars">
                                <?php
                                $stars=$stars[0]['stars'];
                                    for ($index = 1; $index<=$stars; $index++) 
                                    {
                                        echo '<li><i class="fa fa-star color_star"></i></li>';
                                    }
                                    for ($stars ; $stars<5 ; $stars++)
                                    {   echo '<li><i class="fa fa-star"></i></li>';}
                                ?>
                            </ul>
                        </div>
                        <!-- End Service Stars -->
                        
                        <!-- service duration div -->
                        <div class="serviceduration">
                            <p><i class="fa fa-calendar"></i> &nbsp; Service Date : <span class="pull-right"><?php echo $service->getService_date(); ?></span></p>
                            <p id="updateDuration"><i class="fa fa-clock-o"></i> &nbsp; deliver Duration :<span  class="pull-right"> <?php echo $service->getDeliver_date(); ?> days</span></p>
                        </div>
                        <!-- End Service duration div-->
                       
                        <!-- service order status -->
                        <div class="ordersStatus">
                            <p><i class="fa fa-shopping-cart fa-fw"></i> &nbsp; completed orders <span class="pull-right"><?php echo $service->getCompletedOrders(); ?></span></p>
                            <p><i class="fa fa-fw fa-truck"></i> &nbsp; Binding orders <span class="pull-right"><?php echo $service->getBindingOrders(); ?></span></p>
                        </div>
                        <!-- End Order Status-->     
                    </h4>
                </div>
            </div>
            
            <!-- Start Service Owner Div -->
            <div class="col-xs-3">
                <div class="serviceOwner">
                    <h4 class="text-center">Service Owner</h4>
                    <div class="ownerPhoto">
                        <img src="../Images/client_images/<?php echo $Owner['image']; ?>" class="img-circle">
                    </div>
                    <div class="ownerInfo">
                        <h5 class="text-center"><?php echo $Owner['c_name']; ?></h5>
                    </div>
                     <div class="ownerSummary">
                        <h5 class="text-center"><?php echo $Owner['summary']; ?></h5>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- Modal 1 [Edit Info]-->
        <div class="modal fade" id="edit_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align: center;">
                        <h3>Edit Information</h3>
                    </div>
                    <div class="modal-body">
                        <label>Name : </label>
                        <input type="text" name="neme" value="<?php echo $service->getService_name(); ?>" id="Editname" class="form-control" >
                        <label>Description : </label>
                        <textarea name="Description" id="EditDescription" class="form-control"><?php echo $service->getDescription(); ?></textarea>
                        <label>tags : </label>
                        <input type="text" name="tags" value="<?php echo $service->getTags(); ?>" id="Edittags" class="form-control">
                        <label>Duration : </label>
                        <input type="text" name="Duration" value="<?php echo $service->getDeliver_date(); ?>" id="EditDuration" class="form-control">
                        <label>Price : </label>
                        <input type="text" name="price" value="<?php echo $service->getPrice(); ?>" id="Editprice" class="form-control">
                        
                        <div id="error_msg" class="text-danger" style="text-align: center;"></div>
                        <div id="success_msg" class="text-success"style="text-align: center;"></div>
                    </div>
                    <div class="modal-footer">
                        <button  class="btn editPass" type="button" name="submitInfo" data-toggle="modal" id="submitInfo">Save</button>
                        <button type="button" class="btn closeBtn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="user_Estimates col-xs-12">
                <h4 class="text-center">Users Estimates <i class="fa fa-pencil-square-o"></i></h4>
                <?php
                if($estimates)
                {
                    foreach ($estimates as $data) 
                     {
                        echo 
                        ' 
                            <div class="userEstimate col-xs-12">
                                <div class="userInfo col-xs-2">
                                    <img src="../Images/client_images/'.$data['image'].'" class="img-thumbnail">
                                    <h4 class="text-center">'.$data['c_name'].'</h4>
                                </div>
                                <div class="commit col-xs-10">
                                    <p>'.$data['comment'].'<br><br>Rate:
                                        ';
                                        for ($x = 0;$x <$data['stars'];$x++) {

                                            echo '<i class="fa fa-star userStars"></i>';
                                        }
                                        echo'
                                    </p> 
                                    <span class="estimate_date">'.$data['date'].'</span>
                                </div>
                            </div>
                        ' ;
                     }
                }
                else
                {
                    echo '<h3 class="text-center"> there is no estimates </h3>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php 
require './includes/footer.php'; 