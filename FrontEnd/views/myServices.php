<?php
require './includes/header.php';
if(!isset($_SESSION["Client"])&& $client->getRegStatus() != 0)
{
    header("Location: Home.php");
    exit();
}
$id ='';
if(isset($_GET['id']))
{
    $id=$_GET['id'];
    $services = $client->viewClientServices(new Services() , $id);
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
                <h2 style="margin: 20px auto 50px auto;">My Services</h2>
            </div>

            <div class="services_container col-xs-12" >
                <div id="content_search">
                <?php
                if($services)
                {
                foreach ($services as $value)
                {
                    echo '<!--start service -->
                        <div class="item showService col-xs-3"'.(($value['status']==0)?'style="opacity:.5;"':'').'>
                            <div class="thumbnail item-box">
                                <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                                <div class="overlayContainer">
                                    <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                    <a href="EditService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
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
<?php

require './includes/footer.php';
