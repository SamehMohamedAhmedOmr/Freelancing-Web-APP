<?php
require './includes/header.php';
if(isset($_SESSION["Client"])&& $client->getRegStatus() != 0)
{
    $check = 1;
    $client = unserialize($_SESSION["Client"]);
}
else {
  header("Location: Home.php");
}
if(isset($_GET['type']) && isset($_GET['order']) && isset($_GET['c']))
{
  $type = $_GET['type'];
  $order_id = $_GET['order'];
  $client_2 = $_GET['c'];
}
else {
  header("Location: Home.php");
}
?>
<div class="container" style="margin: 20px auto;">
    <div class="row">
      <div id="noc_page" class="oc_page col-xs-12 col-xs-offset-0 text-center">
        <div class="noc_box col-xs-8 col-xs-offset-2 text-center">
          <div class="noc_box_header col-xs-12">New Message</div>
          <div class="noc_box_body">
            <form action="upload.php?type=<?php echo $type;?>&order=<?php echo $order_id;?>&c=<?php echo $client_2;?>" method="post" enctype="multipart/form-data">
              <textarea class="form-control" rows="5" id="noc_message" name="message"></textarea>
              <span>Select file to upload:</span>
              <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
              <?php if($type==2)
              {
                  $o = new order;
                  if( $o->getOrderStatus($order_id,$client->db_connection) == 0 ) // ___________new line
                  echo '<span><input type="checkbox" value="" name="complete">Order Compelete</span>';
              }?>
              <input class="btn noc_btn" type="submit" value="Send" name="uploadSubmit">
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
<?php require './includes/footer.php'; ?>
