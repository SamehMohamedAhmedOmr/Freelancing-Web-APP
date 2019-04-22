<?php 
require './includes/header.php';
$TopServices = $client->viewTopService(new Services());
?>
<!-- overlay load Screen -->
<div class="OverlayLoading">
    <h1>Inuyasha</h1>
    <div class="loading">                
    </div>
</div>
<script>document.title="Home";</script>
<div id="wowslider-container1" style="margin-top: -14px;">
    <div class="ws_images">
        <ul>
            <li><img src="data1/images/b3.png" alt="Blending Speed With Berformance" title="Blending Speed With Berformance" id="wows1_0"/></li>
            <li><img src="data1/images/b5.jpg" alt="work at Home ! No More Time Wasting" title="work at Home ! No More Time Wasting" id="wows1_1"/></li>
            <li><a href="#"><img src="data1/images/b6.jpg" alt="html slider" title="Create your own office" id="wows1_2"/></a></li>
            <li><img src="data1/images/b4.jpg" alt="Welcome in your Home" title="Welcome in your Home" id="wows1_3"/></li>
            </ul>
    </div>
    <div class="ws_bullets">
        <div>
            <a href="#" title="Blending Speed With Berformance"><span><img src="data1/tooltips/b3.png" alt="Blending Speed With Berformance"/>1</span></a>
            <a href="#" title="work at Home ! No More Time Wasting"><span><img src="data1/tooltips/b5.jpg" alt="work at Home ! No More Time Wasting"/>2</span></a>
            <a href="#" title="Create your own office"><span><img src="data1/tooltips/b6.jpg" alt="Create your own office"/>3</span></a>
            <a href="#" title="Welcome in your Home"><span><img src="data1/tooltips/b4.jpg" alt="Welcome in your Home"/>4</span></a>
        </div>
    </div>
    <div class="ws_script" style="position:absolute;left:-99%"><a href="http://wowslider.net">responsive slider</a> by Al_3ameed</div>
    <div class="ws_shadow"></div>
</div>	

<!-- End Slider-->

<!-- Start options Section -->
<section class="options">
    <div class="container-fluid">
        <div class="row col-xs-12">
            <div class="option1 col-xs-4">
                <div class="box1">
                    <i class="fa fa-paypal col-xs-2" aria-hidden="true"></i>
                    <h4 class="col-xs-10"> Paypal</h4>
                    <p> 
                       <b>Safer and protected</b>
                       Shop with peace of mind. We don’t share your full financial information with sellers.
                       And PayPal Buyer Protection covers your eligible purchases if they don’t show up or match 
                       their description.
                    </p>
                </div>
            </div>
            <div class="option2 col-xs-4">
                <div class="box2">
                    <i class="fa fa-home col-xs-2" aria-hidden="true"></i>
                    <h4 class="col-xs-10"><b><span>i</span>nuyasha</b></h4>
                    <p> 
                        Guarantee to deliver the best services to user and following up this services until order completion. <br>
                        contact with customers any time <br> customer service available 24 hours a day   <br>
                        Guarantees  protection and performance to users
                    </p>
                </div>
            </div>
            <div class="option3 col-xs-4">
                <div class="box3">
                    <i class="fa fa-credit-card col-xs-2" aria-hidden="true"></i>
                    <h4 class="col-xs-10"> Credit Card</h4>
                    <p> 
                    Paying with a credit card makes it easier to avoid losses from fraud.
                    When your debit card is used by a thief, the money is missing from your account instantly.
                    Legitimate expenses for which you've scheduled online payments .
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Options Section  -->

<!-- Start Section two [top 10 services]-->
<?php if($TopServices){ ?>
<section class="top_services" id="top_services">
    <div class="container">
        <div class="row">
            <div class="topser_text">
                <h2>Top 10 Services</h2>
            </div>
            <div class="services_container col-xs-12">
                <div class="owl-carousel owl-theme">
                    <?php
                    foreach ($TopServices as $value) 
                    {
                        echo ' 
                        <!--start item -->
                        <div class="item">
                            <div class="thumbnail item-box">
                                <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                                <div class="overlayContainer">
                                    <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                    <a href="viewService.php?ser_id='.$value['s_id'].'"><button class="btn btn-success">Show Item</button></a>
                                    <div class="overlay"></div>
                                </div>
                                <div class="caption">
                                    <h4 class="text-center">'.$value['name'].'</h4>
                                   
                                    <span class="price">'.$value['price'].' $</span>
                                    <ul class="stars">
                                    ';
                                    $stars = $value['stars'];
                                    for ($index = 1; $index<=$stars; $index++) 
                                    {
                                        echo '<li><i class="fa fa-star color_star"></i></li>';
                                    }
                                    for ($stars ; $stars<5 ; $stars++)
                                    {   echo '<li><i class="fa fa-star"></i></li>';}
                                    echo '
                                    </ul>
                                    <div class="timeContainer">
                                        <span class="date">'.$value['s_date'].'</span>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End item -->';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Section 2 -->
<?php } ?>

<!--Section 3 top 4 services in top 4 category -->
<?php 
if($TopCategories)
{
    echo '  <div class="ThirdSectionHeader">
                <h2>Top categories</h2>
            </div>';
    foreach ($TopCategories as $cat) 
    {    
        $option = 'AND s.cat_id = '.$cat['cat_id'].' ';
        $services = $TopServices = $client->viewTopService(new Services() , $option);
        if($services)
        {
            echo ' 
            <section class="top_category">
                <div class="container">
                    <div class="row">
                        <div class="topser_text">
                            <h2>category Name <i class="fa fa-angle-double-right" ></i> <b style="color:#f25c276e;">'.$cat['name'].'</b></h2>
                        </div>
                        <div class="services_container col-xs-12">
                        ';
                        foreach ($services as $value) 
                        {
                            echo '<!--start item -->
                                <div class="item col-xs-3">
                                    <div class="thumbnail item-box">
                                        <span class="view"><i class="fa fa-eye" aria-hidden="true"> </i> '.$value['view'].'</span>
                                        <div class="overlayContainer">
                                            <img src="../Images/service_images/'.$value['image'].'" alt="item">
                                            <a href="viewService.php?ser_id='.$value['s_id'].'" ><button class="btn btn-success">Show Item</button></a>
                                            <div class="overlay"></div>
                                        </div>
                                        <div class="caption">
                                            <h4 class="text-center">Item 1</h4>
                                            <span class="price">'.$value['price'].' $</span>
                                            <div class="timeContainer">
                                                <span class="date">'.$value['s_date'].'</span>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--End item -->';
                        }
                        echo'
                        </div>
                    </div>
                </div>
            </section>
            ';
        }    
    }
}
?>
<!-- End Section 3 -->

<!--Start Section 4 TeamWork -->
<section class="OurTeam text-center" id="team">
    <div class="overlay">
        <div class="ThirdSectionHeader fourthSection">
            <h2>Our Team Work</h2>
        </div>
        <div class="row">
            <div class="team-unit"  style="width: 15%; display: inline-block;">
                <div class="avatar">
                    <img src="../Images/team_work/p1.png" alt="image" class="img-circle">
                    <h4>Sara Ahmed</h4>
                    <p>
                        web designer
                    </p>
                </div>
            </div>
            <div class="team-unit" style="width: 15%; display: inline-block;">
                <div class="avatar">
                    <img src="../Images/team_work/p2.png" alt="image" class="img-circle">
                     <h4>Sameh Mohamed</h4>
                     <p>
                        web developer 
                        <br><br><br><br>
                    </p>
                </div> 
            </div>
            <div class="team-unit" style="width: 15%;display: inline-block;margin-top: -35px;">
                <div class="avatar" style="margin-top: -20px;">
                    <img src="../Images/team_work/p3.jpg" alt="image" class="img-circle">
                    <h4>Hossam Hassan</h4>
                    <p>
                        Full Stack web developer <br>
                        Team Leader <br>
                        <b class="quotes">"Always strive to get on top in life It's the bottom that's overcrowded " </b>
                        <br><br><br><br><br><br>
                    </p>
                </div>
            </div>
            <div class="team-unit" style="width: 15%;display: inline-block;">
                <div class="avatar">
                    <img src="../Images/team_work/p4.png" alt="image" class="img-circle">
                     <h4>Mohamed Abosere</h4>
                    <p>
                        Android developer
                        <br><br><br><br>
                    </p>
                </div>
            </div>
            <div class="team-unit" style="width: 15%; display: inline-block;">
                <div class="avatar">
                    <img src="../Images/team_work/p5.png" alt="image" class="img-circle">
                     <h4>Alia Amr</h4>
                    <p>
                        web designer
                    </p>
                </div>
            </div>
        </div>                
    </div>
</section>
<!--End Section 4 -->

<!-- Section 5 [Testimonium] --> 
<section class="testimonium text-center" id="estimate">
    <div class="overlay"></div>
    <div class="container">
        <div class="ThirdSectionHeader FifthSection">
            <h2>Our Testimonium</h2>
        </div>
        <div id="testim_car" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <li data-target="#testim_car"  data-slide-to="0" class="active"></li>
              <li data-target="#testim_car" data-slide-to="1"></li>
              <li data-target="#testim_car" data-slide-to="2"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner wow rubberBand" role="listbox"  data-wow-duration="4s" data-wow-offset="150">

                <div class="item active lead">
                        <h2> our client says</h2>
                        <img src="../Images/customer_testi/testi1.png" class="img-circle Testimonion_img">
                        <p>
                            this is our client says about our products and our features history ,this is our client says about our products and our features history
                            this is our client says about our products and our features history, this is our client says about our products and our features history
                            <span>"hossam"</span>
                        </p>
                </div>

                <div class="item lead">
                        <h2> our client says</h2>
                        <img src="../Images/customer_testi/testi2.jpg" class="img-circle Testimonion_img">
                        <p>
                            this is our client says about our products and our features history ,this is our client says about our products and our features history
                            this is our client says about our products and our features history, this is our client says about our products and our features history
                            <span>"medo"</span>
                        </p>
                </div>

                <div class="item lead">
                        <h2> our client says</h2>
                        <img src="../Images/customer_testi/testi3.jpg" class="img-circle Testimonion_img">
                        <p>
                            this is our client says about our products and our features history ,this is our client says about our products and our features history
                            this is our client says about our products and our features history, this is our client says about our products and our features history
                            <span>"zaki"</span>
                        </p>
                </div>

            </div>            
        </div>
    </div>
</section>
<!--End Section 5 -->

<!--Start Section 6 brand Section -->
<section class="brand">
    <div class="container">
        <div class="ThirdSectionHeader sixthSection">
            <h2>Our Brands</h2>
        </div>
        <div class="owl-carousel owl-theme col-xs-12">
            <div class="item">
                <img src="../Images/website_images/brands/Joomla!-Logo.svg.png" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/Payoneer-Logo_900x314_high-01.png" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/WordPress-Plugins.png" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/paypal.jpg" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/wooCommerce.png" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/shopify-logo.jpg" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/studio-e63ce757129573e1fae14bcd60ea9e589b07d57be6ee7b868d1aae94d5a91749.png" class="imgconfig">
            </div>
            <div class="item">
                <img src="../Images/website_images/brands/Themeforest-Wordpress-Theme-Customization.jpg" class="imgconfig">
            </div>
        </div>
    </div>
</section>
<!-- End Section 6-->

<!--Additions -->
 <div class="scrollTop">
    <span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true"></span>
</div>

<?php 

include './includes/footer.php';
