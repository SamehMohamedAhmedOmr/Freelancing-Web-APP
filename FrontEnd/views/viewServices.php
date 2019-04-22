<?php 
require './includes/header.php';

if(isset($_GET['cat_id']) && isset($_GET['keyword']))
{
    $searchKey = $_GET['keyword'];
    $id = $_GET['cat_id'];
    $where = "And (service.name LIKE '%$searchKey%' or service.tags LIKE '%$searchKey%')";
    $services = $client->viewServices(new Services() , $id , $where);
}
else if(isset($_GET['cat_id']) && intval($_GET['cat_id']))
{
    $services = $client->viewServices(new Services() , $_GET['cat_id']);
}
else
{
    header("Location: Home.php");
    exit();
}
?>
<script>document.title="View Services";</script>
 <section>
    <div class="container">
        <div class="row">
            <!--Header -->
            <div class="ThirdSectionHeader viewCategorySection" >
                <h2 style="margin: 20px auto 50px auto;">View Services</h2>
            </div>
           
            
                    <!-- services options -->
                <div class="col-xs-3 viewServicesoption text-center">
                    <!-- First Form [Search for service ]-->
                    
                        <h4>Search for service</h4><br>
                        <input type="text" class="form-control" placeholder="search for service" style="margin-bottom: 5px;" id="search"> 
                        <button class="btn btn-default btn-block" id="searching">Search</button>
                    
                    <br>
                    <hr style="border-width: 5px;">
                    <!-- Second Form [select service type ]-->
                    
                        <h4>service type</h4><br>
                        <div class="input-group starsoptions col-xs-12">
                            <label class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>  
                            </label>
                            <input name="5star" type="checkbox" class="star" value="5"> 
                        </div>
                         <div class="input-group starsoptions col-xs-12">
                            <label class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </label>
                             <input name="4star" type="checkbox" class="star" value="4"> 
                        </div>
                         <div class="input-group starsoptions col-xs-12">
                            <label class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </label>
                             <input name="3star" type="checkbox" class="star" value="3"> 
                        </div>
                         <div class="input-group starsoptions col-xs-12">
                            <label class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </label>
                             <input name="2star" type="checkbox" class="star" value="2"> 
                        </div>
                         <div class="input-group starsoptions col-xs-12">
                             <label class="text-warning"><i class="fa fa-star"></i> </label>
                             <input name="1star" type="checkbox" class="star" value="1"> 
                        </div>
                        
                    <hr style="border-width: 5px;">
                    <!-- third Form [service type ]-->
                    
                        <h4>order by</h4><br>
                         <div class="input-group viewservice_orderoption col-xs-12">
                            <label class="text-warning">views Ascending </label>
                            <input name="viewASC" type="checkbox" class="order" value="1"> 
                        </div>

                         <div class="input-group viewservice_orderoption col-xs-12">
                            <label class="text-warning">views Descending </label>
                            <input name="viewDESC" type="checkbox" class="order" value="2"> 
                        </div>

                        <div class="input-group viewservice_orderoption col-xs-12">
                            <label class="text-warning">orders Ascending</label>
                            <input name="orderASC" type="checkbox" class="order" value="3"> 
                        </div>

                        <div class="input-group viewservice_orderoption col-xs-12">
                            <label class="text-warning">orders Descending</label>
                            <input name="orderDESC" type="checkbox" class="order" value="4"> 
                        </div>
                        
                        <hr style="border-width: 5px;">
                        <!-- fourth Form [service price ]-->
                        <h4>range of price</h4><br>
                        <div class="col-sm-12">
                            <div id="slider-range"></div>
                          </div>
                        <div class="row slider-labels" style="margin-top: 30px;">
                            <div class="col-xs-6 caption" style="margin-left: -20px;">
                          <strong>Min:</strong> <span id="slider-range-value1"></span>
                        </div>
                        <div class="col-xs-6 text-right caption">
                          <strong>Max:</strong> <span id="slider-range-value2"></span>
                        </div>
                      </div>
                        <div class="col-sm-12">
                          <input type="hidden" name="min-value" value="">
                          <input type="hidden" name="max-value" value="">
                      </div>
                        <button class="btn btn-default btn-block" id="showPrice" style="margin-top: 10px;">Show</button>
                        
                </div>
                    
                <div class="services_container col-xs-9" >
                    <div id="content_search"> 
                    <?php 
                    if($services)
                    { 
                    foreach ($services as $value) 
                    {
                        echo '<!--start service -->
                            <div class="item showService col-xs-4">
                                <div class="thumbnail item-box">
                                    <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                                    <div class="overlayContainer">
                                        <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                        <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                        <div class="overlay"></div>
                                    </div>
                                    <div class="caption">
                                        <h4 class="text-center" style="font-weight:bolder;">'.$value['name'].'</h4>
                                        <p>'.$value['description'].'</p>
                                        <span class="price">'.$value['price'].' $</span>
                                        <div class="timeContainer">
                                            <span class="date">'.$value['s_date'].'</span>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!--End services -->';
                    }
                }
                else
                {
                    echo '<h4 class="text-center" style="margin:50px auto 200px;">No Services yet , explore other category from <a href="viewCategory.php" class="text-primary">Here</a></h4>';
                }
            ?>
                    </div>
            </div>
        </div>
    </div>
</section>
<script src="../style/js/Services.js"></script>
<?php 
require './includes/footer.php'; 