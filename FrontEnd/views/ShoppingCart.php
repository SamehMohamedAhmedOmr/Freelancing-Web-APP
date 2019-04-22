<?php
require './includes/header.php';

  $check = 0;
  $sc = new ShoppingCart();

  $ClientShppingCart = array();
  $servicesInShppingCart = array();

  if(isset($_SESSION["Client"]))
  {
      $check = 1;
      $client = unserialize($_SESSION["Client"]);
      $clientID =  $client->getID();
      $client->connect();

      if(count($_COOKIE) > 0)
      {
        if(isset($_COOKIE['ShoppingCart']))
        {
          $servicesInShppingCart = unserialize($_COOKIE['ShoppingCart']);
          if(sizeof($servicesInShppingCart) !=0)
          {
            foreach ($servicesInShppingCart as $row) {
              $service = new Services();
              $service->setService_id($row['s_id'],$client->db_connection);
              $client->AddtoCart($service,$row['date'],$sc);
            }
          }
          $servicesInShppingCart=array();
          setcookie ("ShoppingCart",serialize($servicesInShppingCart),time()+ 86400*30,"/"); // 1 day
        }
      }
  }
?>

<div class="container" style="margin: 50px auto 170px auto;">
    <div class="row">
      <div id="sc_page" class="sc_page col-xs-12 col-xs-offset-0 text-center">
        <div id="sc_header" class="sc_header">
          <h3>
            <?php
            if($check == 1)
            {
              $ClientShppingCart = $sc->viewCart($clientID,$client->db_connection);
              if($ClientShppingCart==0)
              {
                $ClientShppingCart=array();
              }
            }
            else {
              if(isset($_COOKIE['ShoppingCart']))
                $ClientShppingCart = unserialize($_COOKIE['ShoppingCart']);
                      if(sizeof($ClientShppingCart) != 0)
                      {
                        $total = 0;
                        foreach ($ClientShppingCart as $s) {
                          $total=+$s["price"];
                        }
                      }
            }
            if(sizeof($ClientShppingCart) != 0)
              echo 'Your Shopping Cart <br>';
            else
              echo 'Your Shopping Cart is Empty';
            ?>
          </h3>
          <?php
          if($check == 0)
            echo '<span> <i class="fa fa-info-circle" aria-hidden="true"></i>Once you add a service to your cart, we will save your spot for 1 month. </span>';
          ?>
          <hr>
        </div>
        <div class="sc_body" id="sc_body">
          <?php
            if(sizeof($ClientShppingCart)!=0)
            {
          ?>
            <table class="table table-condensed text-center sc_table">
                <thead>
                  <tr>
                    <th><div class="sc_tblTh"><i class="fa fa-shopping-basket" aria-hidden="true"></i><div></th>
                    <?php if($check == 1){
                    echo '<th><div class="sc_tblTh"><i class="fa fa-user-o" aria-hidden="true"></i><div></th>';
                    }?>
                    <th><div class="sc_tblTh"><i class="fa fa-usd" aria-hidden="true"></i><div></th>
                    <th><div class="sc_tblTh"><i class="fa fa-clock-o" aria-hidden="true"></i><div></th>
                    <th><div class="sc_tblTh"><i class="fa fa-cogs" aria-hidden="true"></i><div></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ClientShppingCart as $key => $row) {
                    ?>
                    <tr <?php if($row['status']==0){ echo 'class="sc_deactive"'; }?>>
                    <td><?php echo $row["name"]; ?><Button class="sc_tdBtn sc_viewBtn sc_tdBtnShad btn" onclick="window.location.href='viewService.php?ser_id=<?php echo $row['s_id']; ?>'" >View<i class="fa fa-external-link" aria-hidden="true"></i></Button></td>
                    <?php if($check == 1){
                      echo '
                      <td>'. $row["c_name"] .'<a href="Client_Profile.php?action=view_profile&id='. $row["c_id"] .'"><Button class="sc_tdBtn sc_viewBtn sc_tdBtnShad btn">View<i class="fa fa-user-circle" aria-hidden="true"></i></Button></a></td>';
                    } ?>
                    <td><?php echo $row["price"];?> $ </td>

                    <td><?php echo $row['date']; ?></td>

                    <td>
                      <?php
                      if($check == 1 && $row['price'] <= $client->getCoins())
                      {?>
                          <Button <?php if($row['status']==0){echo 'class="sc_tdBtn sc_ordBtn btn" disabled';} else{echo 'class="sc_tdBtn sc_ordBtn sc_tdBtnShad btn"';}?> onclick="updateCoins('<?php echo $client->getID(); ?>','<?php echo $row['price']; ?>','<?php echo $row['s_id']; ?>')" >Order<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></Button>
                      <?php }
                      else if($check == 1 && $row['price'] > $client->getCoins())
                      {?>
                          <Button <?php if($row['status']==0){echo 'class="sc_tdBtn sc_ordBtn btn" disabled';} else{echo 'class="sc_tdBtn sc_ordBtn sc_tdBtnShad btn"';}?> data-toggle="modal" data-target="#sc_hintModal" >Order<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></Button>
                      <?php }
                      else if($check == 0) {?>
                        <a href="login.php">
                          <Button <?php if($row['status']==0){echo 'class="sc_tdBtn sc_ordBtn btn" disabled';} else{echo 'class="sc_tdBtn sc_ordBtn sc_tdBtnShad btn"';}?>>Order<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></Button>
                        </a>
                      <?php } ?>


                      <Button class="sc_tdBtn sc_delBtn sc_tdBtnShad btn"<?php if($check == 1) { ?> onclick="sc_DelServise('<?php echo $client->getID(); ?>','<?php echo $row['s_id']; ?>')" <?php } else { ?> onclick="sc_DelServise_cookie('<?php echo $key; ?>')" <?php } ?> >Remove<i class="fa fa-times-circle" aria-hidden="true"></i></Button>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <hr>
              <div class="sc_totalPrice col-sm-3 col-sm-offset-9">Total Price <?php if($check == 1) {$price = $sc->calcTotalPrice($client->getID(),$client->db_connection); echo $price[0][0]; } else {echo $total ;}?> </div>
            <?php } ?>
        </div>

        <div id="sc_hintModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                You don't have enough coins to make this order.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
</div>
<script>
  function sc_DelServise(c_id,s_id)
  {
    $.ajax({
         cache:false,
         url:"Ajax.php",
         method:"POST",
         data:{c_id:c_id , s_id:s_id , action:'ShoppingCartDel'},
         success:function(data){
              if(data==1)
              {
                  $('#sc_body').load(" #sc_body");
                  $('#sc_header').load(" #sc_header");
              }}
         });
  }
  function sc_DelServise_cookie(del_service)
  {
    $.ajax({
         cache:false,
         url:"Ajax.php",
         method:"POST",
         data:{key:del_service , action:'ShoppingCartDel_cookie'},
         success:function(data){
              if(data==1)
              {
                  $('#sc_body').load(" #sc_body");
                  $('#sc_header').load(" #sc_header");
              }}
         });
  }
  function updateCoins(c_id,s_price,s_id)
  {
    $.ajax({
         cache:false,
         url:"Ajax.php",
         method:"POST",
         data:{c_id:c_id , s_price:s_price , s_id:s_id , action:'updateCoins'},
         success:function(data){
              if(data==1)
              {
                  window.location.href="viewOrders.php";
              }
            }
         });
  }
</script>
<?php require './includes/footer.php'; ?>
