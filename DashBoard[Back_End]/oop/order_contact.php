<?php

 class  order_contact {

/* order_contact Attributes */
   private $date;
   private $sender_id;
   private $reciver_id;
   private $message;
   private $path;


    /*getter & setter methods*/

    function getDate() {
        return $this->date;
    }

    function getSender_id() {
        return $this->sender_id;
    }

    function getReciver_id() {
        return $this->reciver_id;
    }

    function getMessage() {
        return $this->message;
    }

    function getPath() {
        return $this->path;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setSender_id($sender_id) {
        $this->sender_id = $sender_id;
    }

    function setReciver_id($reciver_id) {
        $this->reciver_id = $reciver_id;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setPath($path) {
        $this->path = $path;
    }

public function sendcontact($o_id,$s_id,$r_id,$date,$message,$file,$db)
{
  $sql="INSERT INTO `order_contact`(`o_id`, `date`, `sender_id`, `reciver_id`, `message`, `file_path`)
        VALUES (?,?,?,?,?,?)";
  $Attibutes=array( $o_id,$date,$s_id,$r_id,$message,$file );
  return $db->insert($sql,$Attibutes);
}

public function reciveallcontact($o_id,$db)
{
  $sql = "SELECT `date`, `message`, `file_path`, sender.`c_name` as sender, receiver.`c_name` as receiver, sender.`image` as s_img, receiver.`image` as r_img
          FROM `order_contact` , `customer` as sender , `customer` as receiver
          WHERE sender.c_id = `sender_id` and receiver.c_id = `reciver_id` and `o_id`=?";
  $Attibutes=array
  ( $o_id );
  return $db->select($sql,$Attibutes);
}

 }
