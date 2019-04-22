<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
    require_once('../config/stripe_config.php');
    require './includes/header.php';
    ini_set("max_execution_time",500);
    $token  = $_POST['stripeToken'];
    if(isset($token))
    {
        try
        {
            $customer = \Stripe\Customer::create(array(
            'email' => $client->getE_mail(),
            'source'  => $token
            ));

            $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $_POST['MyRadio'],
            'currency' => 'usd'
            ));

           $charge->__toArray(TRUE);
           $creditcard_name = $charge->source->brand;
           $amount = $charge->amount;
           $currency = $charge->currency;
           $creditcard_id = $charge->id;
           $expire_year = $charge->source->exp_year;
           $expite_month = $charge->source->exp_month;
           $expire_date = $expire_year.'-'.$expite_month.'-00';
           $transaction_date = date("Y-m-d H:i:s");

           // start saving transcation information
           $transaction_process = new Transaction($amount,$transaction_date);
           $creditecard_process = new CreditCard($creditcard_id,$creditcard_name,$expire_date);
           $check =  $client->Transaction_payment($transaction_process,$creditecard_process);

           if($check == 1)
           {
                $mycoins = $client->getCoins();
                // $mycoins = $client->viewcoins($client->getID());
                // $mycoins = $mycoins[0];
                // $mycoins = $mycoins['coins'];
                if($_POST['MyRadio']==10000)
                $updatedCoins = $mycoins+ substr($_POST['MyRadio'], 0, 3);
                else
                 $updatedCoins = $mycoins+ substr($_POST['MyRadio'], 0, 2);
                echo 'updated coins = '. $updatedCoins;
                $client->setCoins($updatedCoins);
                $check_clientCoins = $client->updateCoinse();
                if($check_clientCoins)
                {
                    $client->setCoins($client->getCoins() - $_POST["price"]);
                    $check = $client->updateCoinse($updatedCoins);
                    $client->db_connection->disconnect();
                    $_SESSION['Client'] = serialize($client);

                    $_SESSION['charge_sucess']='<p class="signupsuccess"> charge opreation sucessfully checkout your coins Now</P>';
                    header("Location: payment.php");
                    exit();
                }
                else
                {
                    $_SESSION['charge_fail']='<p class="signuperror">Error :charge opreation failed pls try again later </p>';
                    header("Location: payment.php");
                    exit();
                }
           }
           else
           {
                $_SESSION['charge_fail']='<p class="signuperror">Error :charge opreation failed pls try again later </p>';
               header("Location: payment.php");
               exit();
           }

        } catch (\Stripe\Error\Card $e) {

            $_SESSION['charge_fail']='<p class="signuperror">Error :charge opreation failed pls try again later </p>';
            header("Location: payment.php");
            exit();
        }
        catch (\Stripe\Error\ApiConnection $e) {

            $_SESSION['charge_fail']='<p class="signuperror">Error :Network connection failed. please try again later </p>';
            header("Location: payment.php");
            exit();
        }
        catch (Exception $e) {

            $_SESSION['charge_fail']='<p class="signuperror">Error :Please try again later </p>';
            header("Location: payment.php");
            exit();
        }
    }
    else
    {
        header("Location: Home.php");
        exit();
    }

}
 else
{
    header("Location: Home.php");
    exit();
}
