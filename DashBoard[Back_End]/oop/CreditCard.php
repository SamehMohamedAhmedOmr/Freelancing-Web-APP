<?php


class CreditCard implements PaymentStrategy {
    // Credit Card Attributes
    private $ID;
    private $Name;
    private $EXPDate;
    // Constructor 
    function __construct($id , $name , $exp_date) {
        
        $this->ID=$id;
        $this->Name=$name;
        $this->EXPDate=$exp_date;
    }
    //getter & setter
    function getID() {
        return $this->ID;
    }

    function getName() {
        return $this->Name;
    }

    function getEXPDate() {
        return $this->EXPDate;
    }

    function setID($creditcard_id) {
        $this->ID = $creditcard_id;
    }

    function setName($Name) {
        $this->Name = $Name;
    }

    function setEXPDate($EXPDate) {
        $this->EXPDate = $EXPDate;
    }
     // override Functions   
    public function pay($db) 
    {
        $sql = " INSERT INTO creditcard VALUES (?,?,?)";
        $Attibutes = array($this->getID(), $this->getName(), $this->getEXPDate());
        return $db->insert($sql,$Attibutes);
    }
    public function withdraw() 
    {
        
    }

}
