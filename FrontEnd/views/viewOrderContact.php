<?php
require './includes/header.php';
if(isset($_SESSION["Client"])&& $client->getRegStatus() != 0)
{
    $client = unserialize($_SESSION["Client"]);
}
else {
  header("Location: Home.php");
  exit();
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
  $oc = new order_contact();
  $messages = $oc->reciveallcontact($order_id,$client->db_connection);
?>

<div class="container" style="margin-bottom: 130px; margin-top: 30px;">
    <div class="row">
        <div id="oc_page" class="oc_page col-xs-12 col-xs-offset-0 text-center" style="margin-bottom: 100px;">
        <div class="oc_title">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <h3>Messages</h3>
        </div>
        <a href="newOrderContact.php?type=<?php echo $type;?>&order=<?php echo $order_id;?>&c=<?php echo $client_2;?>">
          <button class="btn noc_btn"><span>New Message </span></button>
        </a>
        <?php if($messages!=0)
        { ?>
        <table class="oc_tbl table table-condensed">
          <tbody>
            <?php foreach ($messages as $row) { ?>
            <tr>
              <td class="col-sm-3">
                <img src="..\Images\\client_images\\<?php echo $row['s_img']; ?>" >
                <span><?php echo $row['sender']; ?></span>
                <br>
                <h5>to : </h5> <span><?php echo $row['receiver']; ?></span>
                <br>
                <h5>at : </h5> <span><?php echo $row['date']; ?></span>
              </td>
              <td class="col-sm-7"><?php echo $row['message']; ?></td>
              <?php if($row['file_path']!='') { ?>
                <td class="col-sm-2"><button class="btn oc_fileBtn"><a href="upload.php?type=<?php echo $type;?>&order=<?php echo $order_id;?>&c=<?php echo $client_2;?>&Download=<?php echo $row['file_path'];?>"><i class="fa fa-download" aria-hidden="true"></i>attached file<a></button></td>
              <?php } else {echo '<td></td>';}?>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } else {
        echo '<div class="col-xs-12"><h5 class="oc_hint">No messages</h5></div>';
      }?>
      </div>
    </div>
</div>

<?php require './includes/footer.php'; ?>
