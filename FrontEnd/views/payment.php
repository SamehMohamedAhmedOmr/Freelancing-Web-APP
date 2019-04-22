<?php require_once('../config/stripe_config.php');
require './includes/header.php';
if(!isset($_SESSION["Client"]) || $client->getRegStatus() == 0)
{
    header("Location: Home.php");
    exit();
}
if($client->getID()==null)
{ header('Location:signup.php'); exit(); }
$transactions = $client->getMyTransactions(new Transaction(0,0));
if(isset($_SESSION['charge_sucess']))
{
    echo $_SESSION['charge_sucess'];
    unset($_SESSION['charge_sucess']);
}
if(isset($_SESSION['charge_fail']))
{
    echo $_SESSION['charge_fail'];
    unset($_SESSION['charge_fail']);
}
if(isset($_SESSION['withdraw_sucess']))
{
    echo $_SESSION['withdraw_sucess'];
    echo '<script>
            var link = document.createElement("a");
            link.href = "output.jpg";
            link.download = "cheque.jpg";
            document.body.appendChild(link);
            link.click();
        </script>';
    unset($_SESSION['withdraw_sucess']);
}
if(isset($_SESSION['withdraw_falied']))
{
    echo $_SESSION['withdraw_falied'];
    unset($_SESSION['withdraw_falied']);
}
?>
<div class="container">
    <div class="ThirdSectionHeader loginHeader">
        <h2>Manage Balance</h2>
    </div>
    <h3 class="text-center" style="background-color: #ffc5b00f; padding: 5px;">your current points :<?php echo $client->getCoins(); ?> points</h3>
    <br><br>
    <!--credit card div -->
    <div class="col-xs-6">
        <div class="box1" style="padding: 30px; padding-bottom: 30px; background-color: #5854530d; margin-bottom: 50px;" >
            <h4>charge you account by credit card <i class="fa fa-credit-card" aria-hidden="true"></i> </h4>
            <form action="creditCard_charge.php" method="post" style="font-family: monospace;">
                <input type="radio" name="MyRadio" value="2000" checked style="margin-right: 15px;">20point=20$<br><br>
                <input type="radio" name="MyRadio" value="50000" style="margin-right: 15px;">50point=50$<br><br>
                <input type="radio" name="MyRadio" value="10000" style="margin-right: 15px;">100point=100$<br><br>
                <br><br>
                <div class="text-center">
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                      data-key="<?php echo $stripe['publishable_key']; ?>"
                      data-description="Buying Coins"
                      data-currency="usd"

                      data-email="<?php echo $client->getE_mail(); ?>"
                      data-amount="<?php echo $_post['MyRadio']; ?>"
                      data-locale="auto">
                    </script>
                </div>
            </form>
        </div>
    </div>
    <!-- paypal div-->
    <div class="col-xs-6">
        <div class="box1"  style="padding: 30px; background-color: #5854530d; margin-bottom: 50px;"  >
            <h4>charge you account by paypal <i class="fa fa-paypal" aria-hidden="true"></i> </h4>
            <form action="#" method="post" style="font-family: monospace;">
                <input type="radio" name="MyRadio" value="2000" checked style="margin-right: 15px;">20point=20$<br>
                <input type="radio" name="MyRadio" value="5000" style="margin-right: 15px;">50point=50$<br>
                <input type="radio" name="MyRadio" value="10000" style="margin-right: 15px;">100point=100$
                <br><br>
                <input type="text" class="form-control" placeholder="Paypal E-mail">
                <div class="text-center">
                    <button  class="btn btn-primary" style="margin: 20px 0;" type="submit" >
                        charge with paypal
                        <i class="fa fa-cc-paypal" aria-hidden="true"></i>
                    </button>

                </div>
            </form>
        </div>
    </div>

    <!--withdraw div -->
    <div class="col-xs-12">
        <div class="box1" style="padding: 30px; padding-bottom: 30px; background-color: #5854530d; margin-bottom: 50px;" >
            <h4>withdraw your coins by cheque <i class="fa fa-money" aria-hidden="true"></i> </h4><br>
            <form action="withdraw.php" method="post" style="font-family: monospace;">
                <input type="radio" name="MyRadio" value="20" checked style="margin-right: 15px;">20$ = 20point  &nbsp;&nbsp;&nbsp;
                <input type="radio" name="MyRadio" value="50" style="margin-right: 15px;">50$ = 50point &nbsp;&nbsp;&nbsp;
                <input type="radio" name="MyRadio" value="100" style="margin-right: 15px;">100$ = 100 points &nbsp;&nbsp;&nbsp;

                <button  class="btn btn-info right" style="float: right; margin-top: -5px;" type="submit" >
                    get your cheque Now
                    <i class="fa fa-usd"></i><i class="fa fa-usd"></i>
                    <i  aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- show transaction div -->
    <div class="col-xs-12" style="height: 600px; overflow-y: auto; margin-bottom: 70px;">
        <h3 class="text-center" style="background-color: #ffc5b00f;">Your transactions</h3>
        <table class="table text-center">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col" class="text-center">transaction type</th>
                <th scope="col" class="text-center">amount</th>
                <th scope="col" class="text-center">date</th>
                <th scope="col" class="text-center">paypal/credit</th>
              </tr>
            </thead>
            <tbody>
        <?php
        $counter=0;
        if($transactions){
        foreach ($transactions as $value) {
            echo '<tr>
                    <th scope="row">'.$counter++.'</th>
                    <td>'.(($value['operation_type']==1)?"charge opreation":"withdraw opreation").'</td>
                    <td>'.(($value['cash']=='10000')?substr($value['cash'], 0, 3):substr($value['cash'], 0, 2)).' $ </td>
                    <td>'.$value['date'].'</td>
                    <td>'.(($value['credit_id']!=NULL)?"creditcard - ".$value['Name']."":"paypal").'</td>
                  </tr>';
        }}
        ?>
            </tbody>
        </table>
    </div>

</div>

<?php
require './includes/footer.php';
