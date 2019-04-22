<?php

class Estimate {

    // Estimate Attributes
    private $star;
    private $comment;
    private $date;
    //Estimate Constructor
    function __construct() {

    }
    //getter & setter
    function getStar() {
        return $this->star;
    }

    function getComment() {
        return $this->comment;
    }

    function getDate() {
        return $this->date;
    }

    function setStar($star) {
        $this->star = $star;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setDate($date) {
        $this->date = $date;
    }
    //class Methods

    public function getOrderEstimate($o_id,$db)
    {
      $sql = "SELECT `comment`,`stars`,`date`
              FROM `estimate`
              WHERE o_id=?";
      $Attibutes=array
      ( $o_id );
      return $db->select($sql,$Attibutes);
    }

    public function setOrderEstimate($o_id,$c_id,$db)
    {
      $sql = "SELECT `s_id` FROM `ordering` WHERE `order_id` = $o_id";
      $data = $db->select($sql,null);
      $s_id = $data[0]['s_id'];
      $sql = "INSERT INTO `estimate`(`comment`, `stars`, `date`, `o_id`, `c_id`, `s_id`) VALUES (?,?,?,?,?,?)";
      $Attibutes=array
      ( $this->getComment(),$this->getStar(),date('Y-m-d H:i:s'),$o_id,$c_id,$s_id);
       return $db->insert($sql,$Attibutes);
    }
    public function showServiceEstimates($Service_id,$db)
    {
      $sql=" SELECT estimate.* , customer.c_name , customer.image
             FROM estimate , customer , service WHERE estimate.c_id=customer.c_id AND service.s_id=estimate.s_id AND service.s_id=?";

      $attributes = array ($Service_id);
      return $data = $db->select($sql,$attributes);
    }
}
