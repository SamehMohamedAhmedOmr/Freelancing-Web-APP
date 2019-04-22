<?php
require './includes/header.php';

if(isset($_SESSION["Client"])&& $client->getRegStatus() != 0)
{
    $client = unserialize($_SESSION["Client"]);
    $o = new order();

    $own_orders = $o->viewOwnOrders($client->getID(),$client->db_connection); // Orders made by client
    $rec_orders = $o->viewReceivedOrders($client->getID(),$client->db_connection);
}
else {
  header("Location: Home.php");
}
?>

<div class="container">
    <div class="row">
      <div id="ord_page" class="ord_page col-xs-12 col-xs-offset-0 text-center">
        <h3 class="ord_title">Your Orders : <h3>
          <hr>
<!-- Table 1 -->
        <?php if($own_orders != 0)
        { ?>
        <table id="own_ord_tbl" class="ord_tbl table table-bordered">
          <thead>
            <tr>
              <th>Order No.</th>
              <th>Service Name</th>
              <th>Price</th>
              <th>Service Provider</th>
              <th>Date</th>
              <th>Status</th>
              <th>Estimate</th>
              <th>Contact</th>
            </tr>
          </thead>
          <tbody>
            <?php
               $count=1; foreach ($own_orders as $row) { ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['price'] ?></td>
                <td><?php echo $row['c_name'] ?></td>
                <td><?php echo $row['date'] ?></td>
                <td class="ord_comp"><?php if($row['status']==1){echo '<i class="fa fa-check-circle" aria-hidden="true"></i>Complete';}else{echo'<i class="fa fa-times-circle" aria-hidden="true"></i>Not Complete';}?></td>
                <td class="ord_est">
                  <?php if($row['status']==1)
                        { $o = new Order();
                          $o->setOrder_id($row['order_id']);
                          
                          $e = new Estimate();
                          $estimate = $o->getOrderEstimate($e,$client->db_connection);
                          if($estimate!=0)
                          {$estimate=$estimate[0]; echo '<button class="btn" data-toggle="modal"  data-target="#ord_estimate" onclick="updateRateModal(\''.$estimate['comment'].'\',\''.$estimate['stars'].'\',\''.$estimate['date'].'\')"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>';}
                          else
                          {echo '<button class="btn" data-toggle="modal"  data-target="#ord_MakeEstimate" onclick="updateMakeEstimateModal(\''.$row['order_id'].'\',\''.$row['s_id'].'\')"><i class="fa fa-unlock-alt" aria-hidden="true"></i></button>';}
                        }
                        else{echo'<i class="fa fa-lock" aria-hidden="true"></i>';}?>
                </td>
                <td>
                    <a href="viewOrderContact.php?type=1&order=<?php echo $row['order_id']; ?>&c=<?php echo $row['s_owner']; ?>">
                      <Button class="ord_tdBtn ord_ordBtn sc_tdBtnShad btn"><i class="fa fa-envelope" aria-hidden="true"></i></Button>
                    </a>
                </td>
              </tr>
              <!-- Modal -->
              <div id="ord_MakeEstimate" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h3>Order Estimate : </h3>
                    </div>
                    <div class="modal-body">
                      <div class="ord_estRate">
                        <span>Rate:</span>
                        <div id="ord_estRate_">
                          <?php
                            $x=1;
                            while($x <= 5)
                            {
                              echo '<a  href="javascript:setOrderEstimate(\''.$x.'\')" id="est_star'.$x.'"> <i class="fa fa-star" aria-hidden="true"></i></a>';
                              $x++;
                            }
                          ?>
                        </div>
                      </div>
                      <div class="ord_estComment">
                        <span>Comment:</span>
                        <div id="ord_estComment_"><textarea name="comment" id="est_comment" class="form-control"></textarea></div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-success" onclick="saveEstimate()">Save</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
              <!--  -->
              <?php $count++; } ?>
          </tbody>
        </table>
      <?php }
          else {
            echo '<h5>No orders exist<h5><br>';
             }?>
        <hr>
        <h3 class="ord_title">Orders Received : <h3>
          <hr>
        <?php if($rec_orders != 0)
        { ?>
<!-- Table 2 -->
        <table class="ord_tbl table table-bordered">
          <thead>
            <tr>
              <th>Order No.</th>
              <th>Service Name</th>
              <th>Client</th>
              <th>Date</th>
              <th>Status</th>
              <th>Estimate</th>
              <th>Contact</th>
            </tr>
          </thead>
          <tbody>
              <?php $count=1; foreach ($rec_orders as $row) {
                $e = new Estimate();
                $estimate = $e->getOrderEstimate($row['order_id'],$client->db_connection);
                $estimate = $estimate[0];
              ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['c_name'] ?></td>
                <td><?php echo $row['date'] ?></td>
                <td class="ord_comp"><?php if($row['status']==1){echo '<i class="fa fa-check-circle" aria-hidden="true"></i>Complete';}else{echo'<i class="fa fa-times-circle" aria-hidden="true"></i>Not Complete';}?></td>
                <td class="ord_est"><?php if($row['status']==1){echo '<button class="btn" data-toggle="modal"  data-target="#ord_estimate" onclick="updateRateModal(\''.$estimate['comment'].'\',\''.$estimate['stars'].'\',\''.$estimate['date'].'\')"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>';}else{echo'<i class="fa fa-window-minimize" aria-hidden="true"></i>';}?></td>
                <td>
                    <a href="viewOrderContact.php?type=2&order=<?php echo $row['order_id']; ?>&c=<?php echo $row['client']; ?>">
                      <Button class="ord_tdBtn ord_ordBtn sc_tdBtnShad btn"><i class="fa fa-envelope" aria-hidden="true"></i></Button>
                    </a>
                </td>
              </tr>

              <!-- Modal -->

              <?php $count++; } ?>
          </tbody>
        </table>
      <?php }
      else {
          echo '<h5>No orders exist<h5><br>';
           }?>
      </div>
      <div id="ord_estimate" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h3>Order Estimate : </h3>
            </div>
            <div class="modal-body">
              <div class="ord_estDate">
                <span>Date: </span>
                <div id="ord_estDate"></div>
              </div>
              <div class="ord_estRate">
                <span>Rate:</span>
                <div id="ord_estRate"></div>
              </div>
              <div class="ord_estComment">
                <span>Comment:</span>
                <div id="ord_estComment"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!--  -->
    </div>
</div>
<script>
function updateRateModal(comment,stars,date)
{
  document.getElementById('ord_estDate').innerHTML=date;
  document.getElementById('ord_estComment').innerHTML=comment;
  document.getElementById('ord_estRate').innerHTML="";
  var c;
  for(c=0 ; c<stars ; c++)
          document.getElementById('ord_estRate').innerHTML+='<i class="colored fa fa-star" aria-hidden="true"></i>';
  for(c=5 ; c>stars ; c--)
          document.getElementById('ord_estRate').innerHTML+='<i class="fa fa-star" aria-hidden="true"></i>';
}
var est_stars = 0;
function setOrderEstimate(n)
{
  est_stars = n;
  var count,count2;
  for(count=1 ; count<=n ; count++)
  {
    starId = 'est_star'+count;
    document.getElementById(starId).style.color="orange";
  }
  for(count2=5 ; count2>=count ; count2--)
  {
    starId = 'est_star'+count2;
    document.getElementById(starId).style.color="grey";
  }
}
var o_id,s_id;
function updateMakeEstimateModal(o,s)
{
  o_id = o;
  s_id = s;
  var count;
  for(count = 1 ; count <= 5 ; count++)
  {
    starId = 'est_star'+count;
    document.getElementById(starId).style.color="grey";
  }
  $('#est_comment').val("");
}
function saveEstimate()
{
  var stars = est_stars;
  var comment = $('#est_comment').val();
  $.ajax({
       cache:false,
       url:"Ajax.php",
       method:"POST",
       data:{o_id:o_id,s_id:s_id,comment:comment,stars:stars, action:'addEstimate'},
       success:function(data){
              if(data==1)
              {
                $('#own_ord_tbl').load(" #own_ord_tbl");
                $('#ord_MakeEstimate').modal('hide');
              }
              else {
                window.alert(data);
              }
          }
       });
}
</script>
<?php require './includes/footer.php'; ?>
