<?php
require_once('../API_lib/Stripe_CreditCard/vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_test_EjsHPbbZ2sa3FQemF8MsUHkh",
  "publishable_key" => "pk_test_7BDGMXQSfW2vNRYW94iE5r3S"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);