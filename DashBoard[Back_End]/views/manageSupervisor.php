
<?php 
require 'includes/Header.php';
if(!$employee->getGroupID() == "0")
{
    header("Location: DashBoard.php");
    exit();
}
//Display Specific employee
if(isset($_GET['action']) && isset($_GET['emp_id']) && $_GET['action']=='view')
{
    $s = new Supervisor();
    $s->setID($_GET['emp_id']);
    $employee->get_supervisor($s);
    // display employee data  
    echo'
    <div class="mngSupr">
        <div class="formBox col-md-offset-2 col-md-8 col-xs-12">
            <div class="boxHeader">
                <h4 class="text-center title">'.$s->getName()."'".'s Profile</h4>
            </div>            
                <div class="boxBody container-fluid text-center">
                    <form class="form-horizontal" action="update_emp.php" method="POST">';
                        // left section
                        echo '<div class="leftSec col-sm-4">    
                                <img class="img-thumbnail" id="autoload" style="width:130px; height:120px;" src="../images/uploads/employee_photos/'.$s->getImage().'"/>
                                <div class="text-center btnSec">
                                   <button type="submit" class="formBtn btn btn-md col-sm-8 col-sm-offset-2" onclick="myFunction()" id="save" Value="Update" Titles="save changes" >Update</button>
                                   <br>
                                   <button type="submit" class="formBtn btn btn-md col-sm-8 col-sm-offset-2" onclick="myFunction()" id="edit" Value="Edit" Title="edit information">Edit</button>
                                </div>
                        </div>';                         
                        //Right Section
                       echo '
                        <div class="rightSec col-sm-8">
                           <div class="form-group">
                                <label class="col-sm-5 control-label text-center" for="usr">ID :</label>
                                <div class="col-sm-7"><input type="number" value="'.$s->getID().'" name="ID" readonly class="form-control colored-tooltip" data-toggle="tooltip" data-original-title="Unable to change!"data-placement="left"></div>
                            </div>
                           <br>   
                           <div class="form-group">
                                <label class="col-sm-5 control-label" for="usr">Name :</label>
                                <div class="col-sm-7"><input type="text"   value="'.$s->getName().'" readonly class="form-control colored-tooltip" data-toggle="tooltip" data-original-title="Unable to change!"data-placement="left"></div>
                           </div>
                           <br>
                           <div class="form-group">
                                <label class="col-sm-5 control-label" for="usr">Email :</label>
                                <div class="col-sm-7"><input type="text"  value="'.$s->getE_mail().'"Name="email" readonly class="form-control colored-tooltip" data-toggle="tooltip" title="Unable to change!"data-placement="left"></div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-sm-5 control-label" for="usr">Date of Hiring :</label>
                                <div class="col-sm-7"><input type="text" value="'.$s->getHireDate().'" readonly  class="form-control colored-tooltip" data-toggle="tooltip" title="Unable to change!"data-placement="left"></div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-sm-5 control-label" for="usr">Number of Hours :</label>
                                <div class="col-sm-7"><input type="number" id="hours" value="'.$s->getHours().'"name="hours" readonly class="form-control "></div>
                            </div>
                            <br>
                           <div class="form-group">
                                <label class="col-sm-5 control-label" for="usr">Salary :</label>
                                <div class="col-sm-7"><input type="number" id="salary" value="'.$s->getSalary().'"name="salary" readonly class="form-control"></div>
                            </div><br>
                        </div>
                    </form>
                </div>                     
            </div>
        </div>
    </div>
</div>';
}
// display All Staff
else
{ 
    $data = $employee->view_all_staff(new Supervisor());    
    echo ' 
    <div id="Dashboard" class="ManageBox tab_content container-fluid">
            <div class="headContainer container-fluid">
                <div class="col-lg-7 col-xs-12 headSec">
                    <form class="form-inline">
                        <button class="col-xs-12 col-md-4 addNewBtn btn btn-success" onclick="location.href=\'addNewSupervisor.php\'; return false;">Add New Employee</button>
                        
                        <div class="col-xs-12 col-md-offset-1 col-md-4 searchSuprInp input-group">
                          <input type="text" class="emp_search input-md form-control" placeholder="searchBy Name or id">
                          <div class="searchIcon input-group-btn">
                            <button class="col-xs-12 btn btn-default btn-md" type="submit">
                              <i class="glyphicon glyphicon-search"></i>
                            </button>
                          </div>
                        </div>
                    </form>
                 </div>

                 <div class="col-xs-12">
                    <div class="tablePanl table-responsive panel panel-default">
                        <table class="table table-hover table-striped table-curved">

                                <thead class="text-center">
                                  <tr>
                                    <th class="SupervisorTblRaw">#</th>
                                    <th class="SupervisorTblRaw">image</th>
                                    <th class="SupervisorTblRaw">Name</th>
                                    <th class="SupervisorTblRaw">mail</th>
                                    <th class="SupervisorTblRaw">view</th>
                                    <th class="SupervisorTblRaw">delete</th>
                                  </tr>
                                </thead>

                                <tbody class="update_ajax">';
                                        if($data!=0)
                                        {
                                            foreach ($data as $value) {
                                                echo ' 
                                                <tr class="text-center">
                                                    <th class="ID-th">'.$value['mgr_id'].'</th>
                                                    <td><img src="../images/uploads/employee_photos/'.$value['image'].'" style="width:30px; height:30px;"></td>
                                                    <td>'.$value['name'].'</td>
                                                    <td>'.$value['E-mail'].'</td>
                                                    <td><ul><li><a href="?action=view&emp_id='.$value['mgr_id'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li></ul></td>
                                                    <td><ul><li><a class="emp_remove_manage" data-value="'.$value['mgr_id'].'"><i class="fa fa-trash" aria-hidden="true" aria-hidden="true"></i></a></li></ul></td>
                                                    </td>
                                                </tr>                            
                                                ';                       
                                            }                   
                                        }
                                        else
                                        { 
                                            echo 'there is No Data';
                                        }
                               echo'      
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
    </div>
    ';
}
 require 'includes/Footer.php'; 