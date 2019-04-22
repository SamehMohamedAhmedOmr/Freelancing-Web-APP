<?php
require 'includes/Header.php';
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
// options
$limit;
$type="All";
((isset($_GET['limit'])&& intval($_GET['limit']) && $_GET['limit']>=0)?$limit=$_GET['limit']:$limit="20");
 // get the type of customers
if(isset($_GET['type']) && ( $_GET['type']=="Activated" || $_GET['type']=="deActivated") )
{$type=$_GET['type'];}

// Post Search
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST['search']))
    {
        $AllData= $employee->showCustomers(new Client(),"0" ,"All");
        $data=[];
        foreach ($AllData as $cust)
        {
            if(strpos($cust['c_name'],$_POST['search'])!==false||strpos($cust['E-mail'],$_POST['search'])!==false)
            {
                $data[]=$cust;
            }
        }
    }
}
else
{
    $data= $employee->showCustomers(new Client(),$limit , $type);
}
if(isset($_GET['action']) && $_GET['action']=="view" && isset($_GET['cust_id']) && intval($_GET['cust_id']))
{
    $client = new Client();
    $client->setID($_GET['cust_id']);
    $data = $employee->getCustomerData($client);
    $customer_services = $employee->getCustomerServices($client);
    ?>

    <div id="Dashboard" class="tab_content container">
      <div class="custView col-xs-10 col-xs-offset-1">

        <div class="viewHeader col-xs-12 text-center">
          <h2 class="viewTitle  col-xs-6 col-xs-offset-3">Customer Information</h2>
        </div>

        <div class="viewContent col-xs-12">

          <?php
          foreach ($data as $value)
          {?>

          <div class="col-xs-4 col-md-offset-1 col-xs-offset-0">
            <div class="custImg">
                <?php
                if(preg_match('/https/',$value['image']))
                {
                    echo '<img class=" user_img imgShadow" src="'.$value['image'].'"></div>';
                }
                else
                {
                    echo '<img class=" user_img imgShadow" src="../../FrontEnd/Images/client_images/'.$value['image'].'"></div>';
                }
                ?>
                
          </div>

          <div class="custInfo col-md-7 col-xs-12">
            <form>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Name">Name:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo $value['c_name']; ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Mail">E-mail:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo $value['E-mail']; ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Pass">Password:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo $value['password']; ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Gender">Gender:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo (($value['gender']==1)?"male":"female"); ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="DoB">Date of Birth:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo $value['dob']; ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Status">Registration Status:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo (($value['regstatus']==1)?"Activated":"deActivated"); ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-5" for="Date">Registration Date:</label>
                    <div class="col-sm-7">
                      <p class="form-control-static"><?php echo $value['dor']; ?></p>
                    </div>
                </div>


            </form>
          </div>
        </div>

        <div class="custServHeader col-xs-12 text-center"> <h4>Customer Services</h4> </div>

        <div class="custServs col-xs-12 text-center">

          <?php
            if($customer_services)
            {
                foreach ($customer_services as $value)
                { echo '<a class="cusServBtn col-lg-4 col-lg-offset-1 col-xs-12 btn text-center" href="manageService.php?action=view&service_id='.$value['s_id'].'">'.$value['name'].'</a>';}
            }
          ?>

        </div>

        <div class="viewFooter col-xs-12 text-center"></div>
      </div>
    </div>

    <?php

  }
}
else
{
   echo '
    <div id="Dashboard" class="tab_content container">

    <div class="showresult"></div>

    <div class="tableContainer col-lg-12 ">

        <div class="cusBtnsSec col-xs-12 ">
            <a class="col-md-offset-1 col-md-3 col-xs-12 btn btn-default allMem" href="?type=All">All Member</a>
            <a class="col-md-3 col-xs-12 btn btn-default actMem" href="?type=Activated">Activated Members</a>
            <a class="col-md-3 col-xs-12 btn btn-default deactMem" href="?type=deActivated">Deactivated Members</a>
        </div>

        <form action="?" method="POST">
            <input type="text" name="search" placeholder="searchBy Name or E-mail">
            <input type="submit" value="search" >
        </form>

        <div>
            <select  class="selectCust form-control" onchange="window.location=\'?limit=\'+this.value">
                <option value="20" '.(($limit==20)?"selected":"").'>20 records</option>
                <option value="40" '.(($limit==40)?"selected":"").'>40 records</option>
                <option value="60" '.(($limit==60)?"selected":"").'>60 records</option>
                <option value="80" '.(($limit==80)?"selected":"").'>80 records</option>
                <option value="0"  '.(($limit==0)?"selected":"").'>All</option>
            </select>
        </div>

            <table class="table service_table table-responsive table-hover table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center"># ID</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">E-mail</th>
                        <th class="text-center">gender</th>
                        <th class="text-center">Date_of_register</th>
                        <th class="text-center">delete</th>
                    </tr>
                </thead>
                <tbody>';
                        if($data)
                        {
                            foreach ($data as $cust)
                            {
                                echo
                                '
                                <tr class="text-center cusTblTr '.(($cust['regstatus']==1)?"service_Activated":"service_deactivated").' ">
                                    <td  class="cusTblTd">'.$cust['c_id'].'</td>';
                                    if(preg_match('/https/',$cust['image']))
                                    {
                                        echo '<td  class="cusTblTd"><img class="img-circle user_img imgShadow" src="'.$cust['image'].'"></td>';
                                    }
                                    else
                                    {
                                        echo '<td  class="cusTblTd"><img class="img-circle user_img imgShadow" src="../../FrontEnd/Images/client_images/'.$cust['image'].'"></td>';  
                                    }
                                    
                                    echo '
                                    <td  class="cusTblTd"><a class="cusID" href="manage_customers.php?action=view&cust_id='.$cust['c_id'].'">'.$cust['c_name'].'</a></td>
                                    <td  class="cusTblTd">'.$cust['E-mail'].'</td>
                                    <td  class="cusTblTd">'.(($cust['gender']==1)?"Male":"Female").'</td>
                                    <td  class="cusTblTd">'.$cust['dor'].'</td>

                                    <td class="cusTblTd"><ul><li><a class="remove_customer" data-id="'.$cust['c_id'].'"><i class="fa fa-trash" aria-hidden="true" aria-hidden="true"></i></a></li></ul></td>

                                </tr>

                                ';
                            }
                        }
                echo'
                </tbody>
            </table>
    </div>
</div>
';
}
require 'includes/Footer.php';
