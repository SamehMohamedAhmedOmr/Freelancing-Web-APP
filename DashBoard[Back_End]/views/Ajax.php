<?php
session_start();
require '../config/includes.php';
if($_SERVER['REQUEST_METHOD']=='POST')
{
    // login 
    if( $_POST['action']=='login' && isset($_POST['Email']) && isset($_POST['pass']) && isset($_POST['option']))
    {
        $E_mail = $_POST['Email'];        
        $password = $_POST['pass'];
        $option = $_POST['option'];
        echo Employee::Dashboard_login($E_mail, $password , $option);   
    }
    // forget password send Mail
    if($_POST['action']=='forget_password' && isset($_POST['Email']))
    {
        $E_mail = $_POST['Email'];
        echo Person::forgetPassword($E_mail);
    }
    // remove supervisor
    if($_POST['action']=='remove_emp' && isset($_POST['emp_id']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $s = new Supervisor();
        $s->setID($_POST['emp_id']);
        $check = $employee->deleteSupervisor($s);
        if($check==1)
        {echo 'remove_success';}
        else
        {echo 'remove_failed';}
    }
    //search for employee
    if($_POST['action']=='search_emp' && isset($_POST['keyword']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $s = new Supervisor();
        $data = $employee->searchForSupervisor($s,$_POST['keyword']);
        if($data)
        {
            foreach ($data as $row) 
            {
                echo ' 
                <tr class="text-center emp_row">
                    <th>'.$row['mgr_id'].'</th>
                    <td><img src="../images/uploads/employee_photos/'.$row['image'].'" style="width:30px; height:30px;"></td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['E-mail'].'</td>
                    <td><a href="?action=view&emp_id='.$row['mgr_id'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                    <td>
                        <a class="emp_remove" data-value="'.$row['mgr_id'].'">
                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>    ';        
            }
        }
    }
    // view my inbox
    if($_POST['action']=='view_inbox')
    {
        $employee = unserialize($_SESSION['Employee']);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage(new Manager_Msg() , null , 'All');}
        else {$data = $employee->readMessage(new Manager_Msg() , null ,$_POST['search']);}        
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificMessage('.$value['mgr_sender'].' ,'.$value['mgr_reciever'].',\''.$value['date'].'\')"
                    '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >'.$value['senderName'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['message'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        { 
            echo 
            '<tr>                     
                <td colspan="3" class="no_message_found">No Message Found</td>
            </tr>';        
        }
    }
    
    
    // view my send messages
    if($_POST['action']=='send_msg')
    {
        $employee = unserialize($_SESSION['Employee']);
        
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage(new Manager_Msg() , $employee->getID(),'All');}
        else {$data = $employee->readMessage(new Manager_Msg() , $employee->getID() ,$_POST['search']);}       
       
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificMessage('.$value['mgr_sender'].' ,'.$value['mgr_reciever'].',\''.$value['date'].'\')"
                        '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >to: '.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['message'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        {echo '
                <tr>                     
                    <td colspan="3" class="no_message_found">No Message Found</td>
                </tr>             
                ';}
    }
    
    
    if($_POST['action']=='view_Supervisorinbox')
    {
        $employee = unserialize($_SESSION['Employee']);
        $m = new CustomerService();
        $m->setKind(3);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage($m,null , 'All');}
        else {$data = $employee->readMessage($m,null ,$_POST['search']);}

        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo '
                    <tr onclick="viewSpecificManagerMessage('.$value['c_id'].' ,'.$value['mgr_id'].',\''.$value['date'].'\')"
                        '.(($value['status']==0)?"class=\"unread\" ":"").' >
                      <td style="width:10%;" >'.$value['senderName'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        { 
            echo 
            '<tr>                     
                <td colspan="3" class="no_message_found">No Message Found</td>
            </tr>';        
        }
    }
    
        // view my send messages
    if($_POST['action']=='supervisor_customer_msg')
    {
        $employee = unserialize($_SESSION['Employee']);
        $m = new CustomerService ();
        $m->setKind(4);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage($m,$employee->getID(),'All');}
        else {$data = $employee->readMessage($m,$employee->getID() ,$_POST['search']);}       
       
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificManagerMessage
                            ('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\',1)"
                                '.(($value['status']==0)?"class=\"unread\" ":"").'>
                                
                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        {
            echo '  <tr>                     
                    <td colspan="3" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

            // accepted Services
    if($_POST['action']=='service_Accepted')
    {
        $employee = unserialize($_SESSION['Employee']);
        $m = new CustomerService ();
        $m->setKind(1);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage($m,$employee->getID(),'All');}
        else {$data = $employee->readMessage($m,$employee->getID() ,$_POST['search']);}       
       
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificManagerMessage
                            ('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\',1)"
                                '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        {
            echo '  <tr>                     
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

                // view my send messages
    if($_POST['action']=='service_unAccepted')
    {
        $employee = unserialize($_SESSION['Employee']);
        $m = new CustomerService ();
        $m->setKind(2);
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->readMessage($m,$employee->getID(),'All');}
        else {$data = $employee->readMessage($m,$employee->getID() ,$_POST['search']);}       
       
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificManagerMessage
                            ('.$value['mgr_id'].' ,'.$value['c_id'].',\''.$value['date'].'\',1)"
                             '.(($value['status']==0)?"class=\"unread\" ":"").'>
                      <td style="width:10%;" >'.$value['to'].'</td>
                      <td style="width:75%;"><b>'.$value['subject'].'</b>- '.$value['content'].'</td>
                      <td style="width:15%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        { 
            echo '  <tr>                     
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }

    if($_POST['action']=='viewReport')
    {
        $employee = unserialize($_SESSION['Employee']);
        $m = new CustomerService ();
        if(isset($_POST['search']) && $_POST['search']=="0")
        {$data = $employee->viewReport($m,$employee->getID(),'All');}
        else {$data = $employee->viewReport($m,$employee->getID() ,$_POST['search']);}       
       
        if($data!=NULL)
        {
            if(is_array($data))
            {
                foreach ($data as $value) 
                {
                   echo ' 
                    <tr onclick="viewSpecificManagerMessage
                            ('.$value['c_id'].' ,'.$value['mgr_id'].',\''.$value['date'].'\')"
                             '.(($value['status']==0)?"class=\"unread\" ":"").' >
                      <td style="width:25%;" >'.$value['senderName'].'</td>

                      <td style="width:25%;" >'.$value['serviceName'].'</td>
                      <td style="width:25%;">
                      <b>'.$value['subject'].'</b>- '.$value['content'].'
                      </td>
                      <td style="width:25%">'.$value['date'].'</td>
                    </tr>
                 '; 
                }                            
            }
        }
        else
        {
            echo '  <tr>                     
                    <td colspan="4" class="no_message_found">No Message Found</td>
                    </tr> ';
        }
    }
    
    // New Message [Manager Staff list]
    if(isset($_POST['action']) && $_POST['action']=='Mail_getStaff' && isset($_POST['mgr_mail']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $data = $employee->Mail_staff_E_mails(new Supervisor() , $_POST['mgr_mail']);
        if(is_array($data))
        {
            echo '<ul class="list-unstyled">';
            foreach ($data as $value) {
                echo '<li class="checkEmployeeName" onclick="selectManagerMail(this)">'.$value['E-mail'].'</li>';
            }
            echo '</ul>';
        }       
    }
    // remove customer infromation permanently from the system
    if(isset($_POST['action']) && $_POST['action']=="customer_remove" && isset($_POST['client_id']) && isset($_POST['client_img']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $client = new Client();
        $client->setID($_POST['client_id']);
        $client->setImage($_POST['client_img']);
        $check = $employee->removeCustomer($client);
        if($check==1){echo 'delete_client_sucess';}
    }
    
    //Member Statistics
    if(isset($_POST['action']) && $_POST['action']=='MemberStatistics')
    {
        //graph customers 
        $employee = unserialize($_SESSION['Employee']);
        $from =(isset($_POST['from'])? $_POST['from']:"2017-01-01");
        $to = (isset($_POST['to'])?$_POST['to']:"2017-12-31");
        $Statistics = $employee->CustomerStatistics(new System() , $from , $to);
        $months='';
        $weight='';
        $returnData='';
        if($Statistics)
        {
            foreach ($Statistics as $value) {
            $months.=$value['Month_Name'].'*';
            $weight .= $value['weight'].'*';
            }
            $returnData=$months.'//'.$weight;
        }
        echo $returnData;
    }
    //cat_serviceStatistics
    if(isset($_POST['action']) && $_POST['action']=="cat_serviceStatistics" && isset($_POST['from']) && isset($_POST['to']) )
    {
        //graph Number of services in each category
        $employee = unserialize($_SESSION['Employee']);
        $Statistics = $employee->serviceStatistics(new Services() , $_POST['from'] , $_POST['to']);
        $category='';
        $services='';
        foreach ($Statistics as $value) {
            $category.=$value['catName'].'*';
            $services .= $value['serviceNum'].'*';
        }
        $returnData=$category.'//'.$services;
        echo $returnData;
    }

    if(isset($_POST['action']) && $_POST['action']=="readNotification")
    {
        $employee = unserialize($_SESSION['Employee']);
        $read = $employee->readNotification(new Notification());
    }
    
}


if($_SERVER['REQUEST_METHOD']=='GET')
{
    // delete subCategory
    if($_GET['action']=='deleteSubCategory')
    {
        $cat_id = $_GET['cat_id'];
        $ceo = new CEO();
        $result=$ceo->deleteCategory($cat_id);
        if($result==1){
            echo 'remove_success';
        }
        else{
            echo 'remove_failed';
        }
    }

    if($_GET['action']=='deleteMainCategory')
    {
        $cat_id = $_GET['cat_id'];
        $ceo = new CEO();
        $result=$ceo->deleteAllSubCategory($cat_id);
        if($result==1){
            $result2 = $ceo->deleteCategory($cat_id);
            if($result2==1){
                echo 'remove_success';
            }
            else{
                echo 'remove_failed';
            }
        }
        else{
            echo 'remove_failed';
        }
    }

    if($_GET['action']=='updateCategory' && isset($_GET['cat_id']) && isset($_GET['name'])
            && isset($_GET['des']) && isset($_GET['order']) && isset($_GET['supervisorID'])
            && isset($_GET['parent']) && isset($_GET['visible']))
    {  
        $employee = unserialize($_SESSION['Employee']);
      
        $cat_id = $_GET['cat_id'];
        $name = $_GET['name'];
        $des = $_GET['des'];
        $order = $_GET['order'];
        $supervisorID = $_GET['supervisorID'];
        $parent = $_GET['parent'];
        $visible = $_GET['visible'];

        $categoy = new Category(); 
        // check Errors , create array to store Errros
        $Errors =[];

        // validate CategoryID
        $check = $categoy->setCat_id($cat_id,$employee->db_connection);
        if($check!=null){$Errors[]=$check;} 

        // validate Name
        $check = $categoy->setCat_Name($name);
        if($check!=null){$Errors[]=$check;}

        // validate category
        $check =$categoy->setCat_Description($des);
        if($check!=null){$Errors[]=$check;}

        //validate Order        
        $check=$categoy->setOrdering($order);
        if($check!=null){$Errors[]=$check;}

        //validate visible
        $check=$categoy->setVisible($visible);
        if($check!=null){$Errors[]=$check;}

        if ($parent==0) {
            $check=$categoy->setParent(0,0,1);
        }
        else{
             $check=$categoy->setParent($parent,$employee->db_connection);
             if($check!=null){$Errors[]=$check;}
        }


        $result;
        if(empty($Errors))
        {
            $result=$employee->editCategory($supervisorID,$categoy);
            if($result==0){
                echo '<p class="input_validate_error">Failed to Update Category pls try again later <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }

            else {
                echo'<p class="input_validate_sucess">Update category  successfully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>'; 
            }      
        }
        else
        {
            foreach ($Errors as $value){
                echo'<p class="input_validate_error">'.$value.'<i class="fa fa-check-square icon" aria-hidden="true"></i></p>';
            }
        }
    }
    //Update service status service
    if(isset($_GET['action']) && $_GET['action']=='updateServiceStatus')
    { 
        $employee = unserialize($_SESSION['Employee']);
        $serviceid = $_GET['serviceid'];
        $Status = $_GET['Status'];

        $Errors =[];
        $service = new Services();
        $check=$service->setService_id($serviceid,$employee->db_connection);
        if($check!=null){$Errors[]=$check;}
        $check=$service->setStatus($Status);
        if($check!=null){$Errors[]=$check;}
        $result;
        if(empty($Errors))
        {
            $customerservice = new CustomerService();
            $result=$employee->updateServiceStatus($service,$customerservice);
            if($result==false){
                echo '<p class="input_validate_error">failed to change status of service<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }
            else 
            {
                echo'success'; 
            }      
        }
        else
        {
            foreach ($Errors as $value){
                echo'<p class="input_validate_error">'.$value.'<i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';
            }
        }
    }

    // remove service
    if($_GET['action']=='remove_service' && isset($_GET['serviceid']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $serviceid = $_GET['serviceid'];

        $service = new Services();
        $check=$service->setService_id($serviceid,$employee->db_connection);
        if($check!=null){$Errors[]=$check;}
        
        $result;
        if(empty($Errors))
        {
            // set images
            $service->setImages($employee->db_connection);
            $ALL_IMAGES = $service->getImages();
            // remove services
            $result=$employee->RemoveService($service);
            if($result==0){
                echo '<p class="input_validate_error">Failed to remove service <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }
            elseif ($result == false) {
                echo '<p class="input_validate_error">Failed to remove service <i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>';
            }
            // if remove service success , remove all images photo from folder services_images
            else 
            {
                if($ALL_IMAGES){
                    foreach ($ALL_IMAGES as $value) 
                    { unlink("../../FrontEnd/Images/service_images/".$value['image']); }
                }
                echo'remove_success'; 
            }      
        }
        else
        {
            foreach ($Errors as $value)
            {
                echo'<p class="input_validate_error">'.$value.'<i class="fa fa-times-circle icon" aria-hidden="true"></i></p>';
            }
        }
    } 

    if($_GET['action']=='search_service' && isset($_GET['keyword']))
    {
        $employee = unserialize($_SESSION['Employee']);
        $keyword = $_GET['keyword'];
        $service = new Services();

        $data = $employee->searchForServices($service,$keyword);
        if($data)
        {
            $counter=1; 
            //var_dump($data);
            foreach ($data as $value) 
            {
                echo ' 
                <tr class="text-center '.(($value['status']==1)?"service_Activated":"service_deactivated").'">
                        <td>'.$value['name'].'</td>
                        <td>'.$value['Category'].'</td>
                        <td>'.$value['Owner'].'</td>
                        <td>'.$value['s_date'].'</td>
                        <td>'.$value['price'].' $</td>
                        <td>'.$value['view'].'<i class="fa fa-eye"></i></td>
                        <td>
                            <select id="serviceStatus" onchange="opensaveButton(this)" class="form-control input-sm">
                                <option '. (($value['status']==1)?"selected":"").' >Activated</option>
                                <option '. (($value['status']==0)?"selected":"").' >Non Activated</option>
                            </select>
                        </td>

                       <td>
                           <a  class="btn btn-sm btn-default" href="?action=view&service_id='.$value['s_id'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                       </td>

                        <td>
                            <button class="service_remove btn btn-sm btn-danger" onclick="removeService('.$value['s_id'].', this)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-default saveButton" disabled="disabled" onclick="updateStatus('.$value['s_id'].', this)">
                               <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>  '; 
            }
        }
    }
}
