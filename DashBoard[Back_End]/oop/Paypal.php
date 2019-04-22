<?php

class Paypal implements PaymentStrategy {
    //paypal Attributes
    private $ID;
    private $paypal_email;
    private $paypal_password;
    // Constructor
    function __construct() {
        
    }
    //getter & setter 
    function getID() {
        return $this->ID;
    }

    function getPaypal_email() {
        return $this->paypal_email;
    }

    function getPaypal_password() {
        return $this->paypal_password;
    }

    function setID($paypal_id) {
        $this->ID = $paypal_id;
    }

    function setPaypal_email($paypal_email) {
        $this->paypal_email = $paypal_email;
    }

    function setPaypal_password($paypal_password) {
        $this->paypal_password = $paypal_password;
    }

    // override Functions    
    public function pay($db) {
        
    }

    public function withdraw() {
        
    }

}
