<?php 
include('includes/Header.php');
if(!$employee->getGroupID() == "1")
{
    header("Location: DashBoard.php");
    exit();
}
?>
<div id="Mail" class="tab_content container"> <!-- start mailbox -->
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
              <i class="fa fa-table"></i>Notification
        </div>
 
           <div class="options col-xs-12">

            </div>
               <div class="card-body">
                    <div class="mailboxTable table-responsive">
                       <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead class="mailBox_header">
                    
                               <tr>
                                   <th >Notification</th>
                                   <th >Date</th>
                               </tr>
                           </thead>
                       <tbody class="mailbox">
                <?php
               $data = $employee->update();
               if($data!=NULL)
               {
                   if(is_array($data))
                   {
                       foreach ($data as $value) 
                       {
                           echo ' 
                           <tr class="messageBoxhover">
                             <td style="width:75%;"><b>'.$value['subject'].'</b> - '.$value['notification'].'</td>
                             <td style="width:15%" class="date">'.$value['date'].'</td>
                           </tr>
                        '; 
                        
                       }                            
                   }
               }
               else
               { echo 'no msg yet';}
            
        ?>
            </tbody>
        </table>
    </div>
        </div>           
                <div class="card-footer small text-muted " >Updated at <?php $date = date('Y-m-d H:i'); echo $date; ?></div>
                </div>
        </div>
      </div>
  </div>

  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->     


</div>  <!-- end mailbox -->
<?php 
 include('includes/Footer.php'); 