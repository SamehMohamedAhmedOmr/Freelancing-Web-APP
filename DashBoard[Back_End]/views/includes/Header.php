<?php 
ob_start();
session_start();
if(!isset($_SESSION["Employee"]))
{
     header("Location: login.php");
     exit();
}
else
{
    require '../config/includes.php';
    $employee = unserialize($_SESSION['Employee']);
    $MailBoxNumber = $employee->countMailBox(new Manager_Msg());

    if($employee->getGroupID() == "1" )
    {
        $customer_servicecounter = $employee->customerServiceCounter(new CustomerService());
        $NotificationCount = $employee->NotificationCounter(new Notification());
    }


}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../style/css/bootstrap.css">
        <link rel="stylesheet" href="../style/css/font-awesome.min.css">

        <link rel="stylesheet" href="../style/css/dashboardStyle.css">
        <link href="../style/css/mailbox.css" rel="stylesheet">
        <link href="../style/css/newmsg.css" rel="stylesheet">
        <link href="../style/css/dataTables.bootstrap4.css" rel="stylesheet">
        <link href="../style/css/manageCateogry.css" rel="stylesheet">
        <link href="../style/css/addSupervisor.css" rel="stylesheet">
        <link href="../style/css/viewSupervisor.css" rel="stylesheet">
        <link href="../style/css/manageservice.css" rel="stylesheet">
        <link href="../style/css/supervisor_category.css" rel="stylesheet">
        <link href="../style/css/manageSupervisor.css" rel="stylesheet">
        <link href="../style/css/manageProfile.css" rel="stylesheet">
        <link href="../style/css/manage-customer.css" rel="stylesheet">
        <link href="../style/css/aboutus.css" rel="stylesheet">

    </head>
    <body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid" style="padding-left: 0px;">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Inuyasha</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-right:20px">
              <div class="nav navbar-nav navbar-right">
                  <!-- <span class="glyphicon glyphicon-search mySearch" aria-hidden="true"></span> -->

                  <div id="Searchform" class="form-group has-feedback">
                  <input type="text" class="seachtext form-control form-rounded" placeholder="Text input">
                  <i class="glyphicon glyphicon-search form-control-feedback" style="color: #b28ca3;" aria-hidden="true"></i>
                  </div>

                <ul class="nav navbar-nav userInfoToogle">
                    <li class="dropdown personalInfoContainer" style="width: 100%; height: 100%;">
                        <img src="../images/uploads/employee_photos/<?php echo $employee->getPhoto_name(); ?>" class="personalImage">
                        <a
                            href="#"
                            class="dropdown-toggle userInfoTitle"
                            data-toggle="dropdown"
                            role="button"
                            aria-expanded="false">
                            <?php echo $employee->getName(); ?>
                            <span class="caret"></span>
                        </a>
                          <ul class="dropdown-menu">
                            <li><a href="manage_profile.php">Profile</a></li>
                            <li><a href="messageBox.php">Mail Box</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="Log_out.php">log Out</a></li>
                          </ul>
                    </li>
                </ul>
              </div>
               <!-- div class NavBar left options -->
              <div class="nav navbar-nav navbar-left">
                  <ul class="navbarOptions">
                      <li>
                          <a href="#menu-toggle" id="menu-toggle"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></a>

                      </li>

                      <li>
                          <span class="myicon glyphicon glyphicon-envelope" aria-hidden="true"></span>
                          <span class="Number_of_Msgs"><?php echo $MailBoxNumber['MailBoxNum']; ?></span>
                      </li>


                      <?php if($employee->getGroupID() == "1" ){
                      ?>
                        <li>
                          <span class="myicon glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                          <span class="Number_of_jobs"><?php echo $customer_servicecounter['C_SNumbers']; ?></span>
                        </li>
                        <li style="background-color: red;">
                            <a href="viewNotification.php" id="NotificationPage">
                            <span class="glyphicon glyphicon-bell" 
                                style="top: 2px;right: -3px;" aria-hidden="true" ></span>
                              <span class="Number_of_Notification"><?php echo $NotificationCount['NotificationNumber']; ?></span>
                            </a>
                        </li>
                      

                      <?php } ?>
                  </ul>
              </div>
            </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <!-- Start of navigation sidebar -->
    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <!-- DashBoard -->
                <li>
                    <a href="DashBoard.php" id="defaultOpen">
                        <div class="textfield-container spinner">
                        </div>
                        <span style="padding-left: 78px;">Dashboard</span>
                  </a>
                </li>
                <hr class="divr">
                <!-- profile -->
                <li>
                    <a href="manage_profile.php">
                        <i class="fa fa-user-circle-o pull-left" style="font-size:25px;"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <hr class="divr">
                <!-- Mail Box -->
                <li>
                    <a href="messageBox.php">
                        <i class="fa fa-envelope-o pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                        Mail Box <?php echo ((($MailBoxNumber['MailBoxNum'])!=0)?"<span class=\"circleMessageNum pull-right\"><b>".$MailBoxNumber['MailBoxNum']."</b></span>":""); ?>
                    </a>
                </li>
                <hr class="divr">
                <!-- Contact Managers -->
                <li>
                    <a href="newMessage.php">
                        <i class="fa fa-comments-o pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                        Contact Managers 
                    </a>
                </li>
              <hr class="divr">
              <?php if($employee->getGroupID() == "0") { ?>
              <!-- Add new Supervisor -->
              <li>
                  <a href="sendNotification.php">
                    <i class="fa fa-bell-o pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Send Notification
                  </a>
              </li>
              <hr class="divr">

              <li>
                  <a href="addNewSupervisor.php">
                    <i class="fa fa-address-card-o pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Add New Supervisor
                  </a>
              </li>
              <hr class="divr">
              <!-- manage supervisor -->
              <li>
                  <a href="manageSupervisor.php">
                    <i class="fa fa-users pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    manage Supervisor
                  </a>
              </li>
              <hr class="divr">
              <!-- Add new Category -->
              <li>
                  <a href="addNewCategory.php">
                      <i class="fa fa-plus-circle pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                      Add New Category
                  </a>
              </li>
              <hr class="divr">
              <!-- Manage Category -->
              <li>
                  <a href="ManageCategory.php">
                    <i class="fa fa-list-ol pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Manage Category
                  </a>
              </li>              
              <hr class="divr">
             
              <?php }else {?>
              <!-- customer services -->
               <!-- Manage Order  -->
              
              <li>
                  <a href="customer_services.php">
                    <i class="fa fa-sticky-note-o pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Customer Service <?php echo (($customer_servicecounter['C_SNumbers'])?"<span class=\" circleMessageNum pull-right\"><b>".$customer_servicecounter['C_SNumbers']."</b></span>":""); ?>
                  </a>
              </li>
              <hr class="divr">
              
              <!-- Manage services -->
              <li>
                  <a href="manageService.php">
                    <i class="fa fa-cogs pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Manage Service
                  </a>
              </li>
              <hr class="divr">
              <!-- My Category -->
              <li>
                  <a href="supervisor_category.php">
                    <i class="fa fa-th-list pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    My Categories
                  </a>
              </li>
              <hr class="divr">
              <!-- Manage Customers -->
              <li>
                  <a href="manage_customers.php">
                    <i class="fa fa-users pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Manage Customers
                  </a>
              </li>
              <!-- Order -->
              <li>
                  <a href="order.php">
                    <i class="fa fa-list-ol pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    Manage order
                  </a>
              </li>              
              <hr class="divr">
              <?php }?>
               <!-- About us -->
              <li>
                  <a href="aboutus.php">
                    <i class="fa fa-info-circle pull-left" style="font-size: 25px;" aria-hidden="true"></i>
                    About us
                  </a>
              </li>              
              <hr class="divr">
              
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
<div class="content">
