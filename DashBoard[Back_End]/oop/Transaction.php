<?php

class Transaction {
    //Payment attributes
    private $dateOfTransaction;
    private $amount;
    //Constructor 
    function __construct($amount , $date) {
        
        $this->amount=$amount;
        $this->dateOfTransaction=$date;
    }
    //getter & setter
    function getDateOfTransaction() {
        return $this->dateOfTransaction;
    }

    function getamount() {
        return $this->amount;
    }

    function setDateOfTransaction($dateOfTransaction) {
        $this->dateOfTransaction = $dateOfTransaction;
    }

    function setamount($amount) {
        $this->amount = $amount;
    }

   
    // class Methods
    public function saveTransaction_data($paymenth_type , $payment_id , $customer_id , $db)
    {
        $paypal_id ='';
        $credit_id ='';
        if($paymenth_type=="CreditCard")
        {
            $paypal_id=NULL;
            $credit_id=$payment_id;
        }
        else if ($paymenth_type=="Paypal")
        {
            $paypal_id=$payment_id;
            $credit_id=NULL;
        }
        
        $sql = "INSERT INTO transaction VALUES (?,?,?,?,?,1)";
        $attributes = array($customer_id, $this->getDateOfTransaction(), $paypal_id , $credit_id , $this->getamount());
        return $db->insert($sql,$attributes);
    }
    // Payment methods
    public function doPay(PaymentStrategy $ps ,  $db)
    {
       return $ps->pay($db);
    }
    
    public function getMyTransactions($customer_id , $db)
    {
        $sql=
                "SELECT * from transaction 
                LEFT JOIN creditcard on creditcard.`credit_id` = transaction.`credit_id`
                WHERE transaction.customer_id=?
                order by transaction.date desc";
        $attributes= array($customer_id);
        return $db->select($sql,$attributes);
    }
}
