<?php 
include('includes/Header.php'); 
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
?>
<?php
$order= new Order();
$type="All";
if(isset($_GET['type']) && ($_GET['type']=="Completed" || $_GET['type']=="unCompleted"))
{$type=$_GET['type'];}
$alldata= $employee->viewOrder(new Order() , $type);   
    echo ' 
    <div id="Dashboard" class="tab_content container-fluid">
          <h2 class="text-center">Manage Order </h2> 
           <!-- view opttions -->
            <a href="order.php?type=All" class="pull-right btn btn-primary" style="margin: 10px;">All Orders</a>
            <a href="order.php?type=Completed" class=" pull-right btn btn-primary" style="margin: 10px;">Completed Order</a>
            <a href="order.php?type=unCompleted" class="pull-right btn btn-primary" style="margin: 10px;">unCompleted Order</a>
            
         <div class="col-xs-12">
            <div class="tablePanl panel panel-default">
                <table class="table table-hover table-striped table-curved">
                        <thead class="text-center">
                          <tr>
                           <th class="SupervisorTblRaw">customer E-mail</th>
                            <th class="SupervisorTblRaw">customer name</th>
                            <th class="SupervisorTblRaw">order id</th>
                            <th class="SupervisorTblRaw">order date</th>
                           <th class="SupervisorTblRaw">service name</th>
                          <th class="SupervisorTblRaw">status</th>
                          </tr>
                        </thead>

                        <tbody class="update_ajax">';
                                
                                if($alldata!=0)
                                {
                                    foreach ($alldata as $value) {
                                        $stat=$value['stat'];
                                        if($stat==1)
                                        {
                                        echo ' 
                                        <tr class="text-center emp_row">
                                            <td bgcolor="#ccffcc">'.$value['E-mail'].'</td>
                                            <td bgcolor="#ccffcc"><a href="manage_customers.php?action=view&cust_id='.$value['c_id'].'">'.$value['c_name'].'</a></td>
                                            <td bgcolor="#ccffcc">'.$value['order_id'].'</td>
                                            <td bgcolor="#ccffcc">'.$value['date'].'</td>
                                            <td bgcolor="#ccffcc"><a href="manageService.php?action=view&service_id='.$value['s_id'].'">'.$value['name'].'</a></td>
                                            <td bgcolor="#ccffcc">Completed</td>
                                                </a>
                                            </td>
                                        </tr>                            
                                        ';                       
                                    } 
                                    else
                                    {
                                    	echo ' 
                                        <tr class="text-center emp_row">
                                            <td bgcolor="#ffa099">'.$value['E-mail'].'</td>
                                            <td bgcolor="#ffa099"><a href="manage_customers.php?action=view&cust_id='.$value['c_id'].'">'.$value['c_name'].'</a></td>
                                            <td bgcolor="#ffa099">'.$value['order_id'].'</td>
                                            <td bgcolor="#ffa099">'.$value['date'].'</td>
                                            <td bgcolor="#ffa099"><a href="manageService.php?action=view&service_id='.$value['s_id'].'">'.$value['name'].'</a></td>
                                            <td bgcolor="#ffa099">on hold</td>
                                                </a>
                                            </td>
                                        </tr>                            
                                        ';                       
                                    }  

                                        }               
                                }
                                else
                                {
                                    echo 'there  No Data';
                                }
                       echo'      
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    ';
?>
<?php
 include('includes/Footer.php'); 