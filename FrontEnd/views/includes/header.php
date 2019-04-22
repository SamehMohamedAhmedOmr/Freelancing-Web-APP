<!DOCTYPE html>
<?php
ob_start();
session_start();
require '../config/includes.php';
if( isset($_COOKIE['E_mail']) && isset($_COOKIE['pass']))
{
    $mail = $_COOKIE['E_mail'];
    $password = $_COOKIE['pass'];
    $return_data = Client::website_login($mail,$password,'true');
}

if(isset($_SESSION["Client"]))
{
    $client = unserialize($_SESSION["Client"]);
}
 else {
    $client = new Client();
    $client->connect();
}
$TopCategories = $client->TopCategory(new Category);
$MainCat = $client->viewMainCategory(new Category);
$messageCount = $client->messageCounter();
$messageNumber=  $messageCount[0]['MsgNumber'];
$customerServiceCounter = $client->customerServiceCounter();
$customerServiceCounter=  $customerServiceCounter[0]['myMessageNumber'];
?>
<html>

    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../../DashBoard[Back_End]/style/css/font-awesome.min.css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="../../DashBoard[Back_End]/style/css/bootstrap.css">
        <link href="https://fonts.googleapis.com/css?family=Amaranth" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
        <!-- Start WOWSlider.com HEAD section -->
        <link rel="stylesheet" type="text/css" href="engine1/style.css" />
        <script type="text/javascript" src="engine1/jquery.js"></script>
        <!-- End WOWSlider.com HEAD section -->
        <!-- start wol product cursor -->
        <link rel="stylesheet" href="../style/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../style/css/owl.theme.default.min.css">
        <!-- End wol-->

        <!-- pages style -->
        <link rel="stylesheet" href="../style/css/Home.css">
        <link rel="stylesheet" href="../style/css/viewCategory.css">
        <link rel="stylesheet" href="../style/css/contact_us.css">
        <link rel="stylesheet" href="../style/css/login.css">
        <link rel="stylesheet" href="../style/css/viewServices.css">
        <link rel="stylesheet" href="../style/css/viewspecificService.css">
        <link rel="stylesheet" href="../style/css/profile.css">


        <link rel="stylesheet" href="../style/css/ShoppingCart.css">
        <link rel="stylesheet" href="../style/css/newOrderContact.css">
        <link rel="stylesheet" href="../style/css/viewOrderContact.css">
        <link rel="stylesheet" href="../style/css/viewOrder.css">
        <link rel="stylesheet" href="../style/css/MassegenerBox.css">

        <link rel="stylesheet" href="../style/css/addNewService.css">
        <link rel="stylesheet" href="../style/css/viewServices.css">

        <link rel="stylesheet" href="../style/css/web_site_contact.css">



    <script>
        $('html').css('overflow', 'hidden');
    </script>
    </head>


    <body>
        <!-- Start Header -->
        <header>
            <div class="top_header">
                <div class="container">
                    <div class="row">
                        <div class="left col-xs-10">
                            <ul class="left_options" style="padding: 0; font-size: 13.5px;">
                                <?php
                                    if(isset($_SESSION["Client"]))
                                    {
                                        echo '<li><a href="client_profile.php?id='.$client->getID().'"><span><i class="fa fa-user-circle-o"></i> Profile </span></a></li> ';
                                        if($client->getRegStatus() != 0)
                                        {
                                            echo '<li><a href="viewOrders.php"><span><i class="fa fa-table"></i> My Orders</span></a></li> ';
                                            echo '<li>
                                                  <a href="MassengerBox.php">
                                                  <span>
                                                  <i class="fa fa-comments">';
                                                  if($messageNumber!=0){
                                                    echo '<i class="Noti"> '.$messageNumber.'</i>';
                                                  }
                                            echo '</i> Your Message </li> ';

                                            echo '<li>
                                                <a href="web_site_contact.php">
                                                <span>
                                                <i class="fa fa-envelope-o">';

                                                if($customerServiceCounter!=0){
                                                  echo '<i class="customerServiceNoti"> '.$customerServiceCounter.'</i>';
                                                }
                                            echo '</i> web-site Contact </li> ';
                                       }

                                          if($client->getRegStatus() != 0)
                                          {
                                            echo '<li><a href="AddNewService.php"><span><i class="fa fa-plus-square-o" aria-hidden="true"></i>New Service</li>';
                                            echo '<li><a href="myServices.php?id='.$client->getID().'"><span><i class="fa fa-archive"></i> My Services</span></a></li> ';
                                            echo '<li><a href="payment.php"><span><i class="fa fa-money"></i>Balance</span></a></li>';

                                          }
                                          echo '<li><a href="ShoppingCart.php"><span><i class="fa fa-cart-plus"></i>Shopping cart</span></a></li>';
                                          echo '<li><a href="log_out.php"><span><i class="fa fa-sign-out" aria-hidden="true"></i>log out</a></li> ';
                                    }
                                    else
                                    {
                                        echo '
                                        <li><a href="login.php"><span><i class="fa fa-sign-in"></i>Login</span></a></li>
                                        <li><a href="signup.php"><span><i class="fa fa-user-plus"></i>Sign up</span></a></li>
                                        <li><a href="ShoppingCart.php"><span><i class="fa fa-cart-plus"></i>Shopping cart</span></a></li>';
                                    }

                                ?>

                            </ul>
                        </div>
                        <div class="col-xs-2 right">
                            <div class="lang_container">
                                <div class="lang"><i class="fa fa-globe"></i> Language <i class="fa fa-angle-down"></i> </div>
                                <div class="lang_options">
                                    <ul>
                                        <li>English</li>
                                        <li>Arabic</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="Middle_header">
                <div class="container" style="padding: 0;">
                    <div class="leftLogo col-xs-3">
                        <b onclick="location.href='Home.php'" style="cursor: pointer;"><span>i </span>nuyasha</b>
                    </div>
                    <div class="rightSearch col-xs-9" style="padding: 0;">
                        <div class="row">
                            <form class="col-xs-12 searchForm" style="padding: 0;" action="viewServices.php" method="GET">
                                <div class="form-group col-xs-3" style="padding-right: 0;">
                                    <select class="form-control input-lg selectCat" name="cat_id">
                                        <option disabled selected>Select Category</option>
                                        <?php
                                            foreach ($MainCat as $cat) {
                                                echo '<option value="'.$cat['cat_id'].'">'.$cat['name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-xs-7" style="padding-left: 0;">
                                    <input type="text" name="keyword" class="form-control input-lg searchService" placeholder="SearchHere" id="searchKey">
                                </div>
                                <div class="form-group col-xs-2" style="padding-right: 0;">
                                    <button type="submit" class="form-control input-lg" id="searchBtn">
                                        Search
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="Bottom_header">
                <div class="container">
                    <div class="row" style="margin-bottom: -10px;">
                        <div class="col-xs-3" style="padding: 0;">
                            <div class="AllCategoreis">
                                <i class="fa fa-th-list"></i>
                                <b onclick="location.href = 'viewCategory.php';" style="cursor: pointer;">All Category</b>
                                <div class="topCategory">
                                    <?php
                                    echo '<ul>';
                                    foreach ($TopCategories as $cat) {
                                        echo '<li><a href="viewServices.php?cat_id='.$cat['cat_id'].'">'.$cat['name'].'</a><i class="fa fa-chevron-down pull-right" style="color:#fff;" aria-hidden="true"></i></li>';
                                    }
                                    echo '<li><a href="viewCategory.php">All Category</a><i class="fa fa-arrows-alt pull-right" style="color:#fff;"></i></li>';
                                    echo '</ul>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="menuList col-xs-8" style="padding: 0;">
                            <ul>
                                <li><a style="text-decoration: none; color: inherit;" href="Home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                <li><a style="text-decoration: none; color: inherit;" href="Home.php #top_services"><i class="fa fa-level-up" aria-hidden="true" style="transition: all ease-in-out 1.5s;"></i>Top Services</a></li>
                                <li><a style="text-decoration: none; color: inherit;" href="Home.php #team"><i class="fa fa-users" aria-hidden="true"></i>Our Team</a></li>
                                <li><a style="text-decoration: none; color: inherit;" href="Home.php #estimate"><i class="fa fa-book" aria-hidden="true"></i>Estimates</a></li>
                                <li><a style="text-decoration: none; color: inherit;" href="contact.php"><i class="fa fa-comment-o" aria-hidden="true"></i>Contact</a></li>
                            </ul>
                        </div>
                        <div class="col-xs-1" style="padding: 0;">
                            <div class="icons">
                                <div class="shoppingCart col-xs-6">
                                    <i class="fa fa-shopping-cart">
                                        <span class="shop_cart_num"><?php $sc=new ShoppingCart(); $sc->getCount(($client->getID()!="")?$client->getID():"-1",$client->db_connection); ?></span>
                                    </i>
                                </div>
                                <div class="search col-xs-6">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->
