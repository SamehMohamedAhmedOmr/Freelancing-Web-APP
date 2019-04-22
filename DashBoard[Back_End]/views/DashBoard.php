<?php 
require 'includes/Header.php'; 
$orderNum = $employee->countOrder(new System());
?>
<div id="Dashboard" class="tab_content container">
    <?php 
        if($employee->getGroupID() == "0" )
        {
            echo '<h1 class="text-center">Welcome Admin ['.$employee->getName().']</h1>'; 
            $supervisorNum = $employee->CountSupervisors(new System());
            $categoryNum = $employee->countCategory(new System()); 
            $totalRevenue = $employee->TotalRevenueinLatestYear(new Order());
            echo '<input type="hidden" name="clientStatistics" id="clientStatistics">';
        }
        else
        {
            echo '<h1 class="text-center">Welcome Supervisor ['.$employee->getName().']</h1>';
            $latest_services=$employee->viewServices(new Services() , $employee->getID() , 10);
            $latest_users = $employee->showCustomers(new Client() , "10" , "");
            $MemberStatus = $employee->DashboardMembers(new System());
            $TotalserviceNum = $employee->countServicesNum(new System());
            $ActivatedserviceNum = $employee->countServicesNum(new System() , "Activated");
            $deActivatedserviceNum = $employee->countServicesNum(new System() , "deActivated");
        }
    ?>
    
      <!-- start 4 divs -->
        <div class="row">
            <?php
            if($employee->getGroupID() == "1" )
            {
                echo '
                <div class="col-md-4">
                    <div class="stat st-Mem" >
                        <span class="pull-left"><i class="fa fa-users fa-lg"></i></span>
                        <div class="info pull-right">
                        Total Members
                        <span><a href="manage_customers.php?type=All">'.$MemberStatus['AllCustomers'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat st-BMem" style="background-color: #3498db;">
                        <span class="pull-left"><i class="fa fa-user-times fa-lg"></i></span>
                        <div class="info pull-right">
                         Bind Members
                        <span><a href="manage_customers.php?type=deActivated">'.$MemberStatus['NonActivated'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat st-BMem">
                        <span class="pull-left"><i class="fa fa-user-plus fa-lg"></i></span>
                        <div class="info pull-right">
                         Active Members <br>
                        <span><a href="manage_customers.php?type=Activated">'.$MemberStatus['Activated'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat st-IT" style="background-color:#7f8c8d;">
                        <span class="pull-left"><i class="fa fa-handshake-o fa-lg"></i></span>
                        <div class="info pull-right">
                            Total Services Num
                            <span><a href="manageService.php">'.$TotalserviceNum['serviceNum'].'
                                <i style="font-size:18px;" class="fa fa-arrow-up pull-right"></i> <i style="font-size:18px;" class="fa fa-arrow-down pull-right"></i></a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat st-IT" style="">
                        <span class="pull-left"><i class="fa fa-handshake-o fa-lg"></i></span>
                        <div class="info pull-right">
                            Activated Services
                            <span><a href="manageService.php?type=Activated">'.$ActivatedserviceNum['serviceNum'].'<i class="fa fa-arrow-up pull-right"></i></a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat st-IT" style="background-color:#c0392b;">
                        <span class="pull-left"><i class="fa fa-handshake-o fa-lg"></i></span>
                        <div class="info pull-right">
                            deActivated Services
                            <span><a href="manageService.php?type=deActivated">'.$deActivatedserviceNum['serviceNum'].'<i class="fa fa-arrow-down pull-right"></i></a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat st-IT" style="background-color:#7f8c8d;">
                        <span class="pull-left"><i class="fa fa-cart-plus fa-lg"></i></span>
                        <div class="info pull-right">
                            Orders Numbers
                            <span><a href="order.php?type=All">'.$orderNum['AllOrders'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat st-IT">
                        <span class="pull-left"><i class="fa fa-cart-plus fa-lg"></i></span>
                        <div class="info pull-right">
                            Orders Numbers
                            <span><a href="order.php?type=Completed">'.$orderNum['Completed'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                    
                <div class="col-md-4">
                    <div class="stat st-IT" style="background-color:#c0392b;">
                        <span class="pull-left"><i class="fa fa-cart-plus fa-lg"></i></span>
                        <div class="info pull-right">
                            Orders Numbers
                            <span><a href="order.php?type=unCompleted">'.$orderNum['unCompleted'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>';
            }
            else
            {
                echo 
                '<div class="col-md-4">
                    <div class="stat st-Co" style="background-color: #ecf0f1;">
                        <span class="pull-left"><i class="fa  fa-lg fa-th-list"></i></span>
                        <div class="info pull-right">
                            category Num
                            <span><a href="ManageCategory.php">'.$categoryNum['categoryNum'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat st-Co">
                        <span class="pull-left"><i class="fa  fa-lg fa-users"></i></span>
                        <div class="info pull-right">
                            Supervisor Num
                            <span><a href="manageSupervisor.php">'.$supervisorNum['CountSupervisors'].'</a></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="stat st-Co" style="background-color: #e81919;">
                        <span class="pull-left"><i class="fa  fa-lg fa-money"></i></span>
                        <div class="info pull-right">
                            Incoming Revenue
                            <span style="font-size:22px;"><a href="AdminStatistics.php">'.$totalRevenue['money'].' $ <i class="fa fa-line-chart pull-right" aria-hidden="true"></i></span> </a>
                        </div>
                        <div class="clearfix" style="padding-bottom:25px;"></div>
                    </div>
                </div>
                
                ';
            }
            ?>
        </div>
      <!-- End 4 divs -->
      
        <?php if($employee->getGroupID() == "0" ){
            echo ' 
             <div class="col-xs-12" style="margin:15px 0px; background-color:#fff;">
                <label class="text-danger col-xs-12 text-center" style="padding: 15px;" >Users Flow</label><br><br>
                <div class="row col-xs-12">
                    <!-- start graph div -->
                        <div class="col-xs-12">
                            <div class="col-xs-12" style="background-color: #fff; ">
                                <canvas id="userCharts1"></canvas>
                            </div>
                        </div>
                    <!--End graph div -->
                </div>
            </div>
        ';}
       else 
        {
            ?> 
                <div class="latest">
                    <div class="row col-xs-12">
                        <!-- start latest 10 users -->
                        <div class="col-sm-6" style="padding-left: 0;">
                            <div class="panel panel-default latestContainer">
                                <div class="panel-heading" style="background-color: #f1bcb4 !important;">
                                    <i class="fa fa-users "></i>
                                    latest 10 users
                                    <span class="pull-right showDashboardData"><i class="fa fa-plus"></i></span>
                                </div>
                                <div class="panel-body latestBody">
                                    <ul class="list-unstyled LatestItems">
                                        <?php
                                        foreach ($latest_users as $data) {
                                            echo '<li style="position:relative;"><a href="manage_customers.php?action=view&cust_id='.$data['c_id'].'">'.$data['c_name'].'</a><span class="dashservicedate">'.$data['dor'].'</span></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End latest 10 users -->    
                        
                        <!-- start latest 10 services -->
                        <div class="col-sm-6" style="padding-left: 0;">
                            <div class="panel panel-default latestContainer">
                                <div class="panel-heading" style="background-color: #f1bcb4 !important;">
                                <i class="fa fa-tag "></i>
                                latest 10 Services
                                <span class="pull-right showDashboardData"><i class="fa fa-plus"></i></span>
                                </div>
                                <div class="panel-body" style="padding: 0;">
                                <ul class="list-unstyled LatestItems" >
                                       <?php
                                        foreach ($latest_services as $data) {
                                            echo '<li style="position:relative;"><a href="manageService.php?action=view&service_id='.$data['s_id'].'">'.$data['name'].'</a><span class="dashservicedate">'.$data['s_date'].'</span></li>';
                                        }
                                       ?>
                                  </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End latest 10 services -->    
                    </div>
                </div>      
            <?php
       } 
       ?>
</div>
<?php 
if($employee->getGroupID() == "0" )
{
    echo'
        <script src="../style/js/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
        <script src="../style/js/DashboardGraph.js"></script>'; 
}
 require 'includes/Footer.php';     