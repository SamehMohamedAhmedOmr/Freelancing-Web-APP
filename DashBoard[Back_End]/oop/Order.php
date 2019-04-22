<?php

class Order {
   // Order Attributes
   private $Order_id;
   private $orderDate;
   private $requiredDate;
   private $status;
   private $order_contact;
   //Order Constructor
   function __construct() {
   }
   //getter & setter Methods
   function getOrder_id() {
       return $this->Order_id;
   }

   function getOrderDate() {
       return $this->orderDate;
   }

   function getRequiredDate() {
       return $this->requiredDate;
   }

   function getStatus() {
       return $this->status;
   }

   function setOrder_id($Order_id) {
       $this->Order_id = $Order_id;
   }

   function setOrderDate($orderDate) {
       $this->orderDate = $orderDate;
   }

   function setRequiredDate($requiredDate) {
       $this->requiredDate = $requiredDate;
   }

   function setStatus($status) {
       $this->status = $status;
   }
   function getOrder_contact() {
       return $this->order_contact;
   }

   function setOrder_contact($order_contact) {
       $this->order_contact = $order_contact;
   }

// class Methods
     public function placeOrder ($c_id, $s, $db_connection)
    {
         $status = 0;
         $date = date("Y-m-d H:i:s");
         $sql = "INSERT INTO `ordering`(`date`, `status`, `s_id`, `c_id`) VALUES (?,?,?,?)";
         $Attibutes=array
         (
             $date, $status, $s->getService_id(), $c_id
         );
         return $db_connection->insert($sql,$Attibutes);
    }

    public function viewOwnOrders ($c_id,$db)
    {
      $sql = "SELECT ordering.order_id, ordering.date, ordering.status, ordering.s_id, service.name, service.price, service.c_id as s_owner, customer.c_name
              FROM `ordering` , `service` , `customer`
              WHERE ordering.c_id = ? and ordering.s_id=service.s_id and service.c_id=customer.c_id order by ordering.date desc";
      $Attibutes=array
      ( $c_id );
      return $db->select($sql,$Attibutes);
    }

    public function viewReceivedOrders ($s_owner,$db)
    {
      $sql = "SELECT ordering.order_id, ordering.date, ordering.status, ordering.s_id, ordering.`c_id`as client,
              service.name, service.c_id as owner, customer.c_name
              FROM `ordering` , `service` , `customer`
              WHERE ordering.s_id=service.s_id and ordering.c_id=customer.c_id and service.c_id=? order by ordering.date desc ";
      $Attibutes=array
      ( $s_owner );
      return $db->select($sql,$Attibutes);
    }

    public function estimateOrder(Estimate $e, $c_id, $db_connection)
    {
      return $e->setOrderEstimate($this->Order_id, $c_id, $db_connection);
    }

    public function getOrderEstimate(Estimate $e,$db)
    {
      return $e->getOrderEstimate($this->getOrder_id(),$db);
    }

    //------------------------------------------------------------
    public function viewOrder ($db , $type)
    {
        if($type=="Completed")
        {$type=" AND o.status = 1 ";}

        elseif($type=="unCompleted")
        {$type=" AND o.status = 0 ";}
        else
        {
            $type="";
        }

        $Query="select *, o.status as stat, c.c_name,c.`E-mail` , s.name from ordering o,customer c,service s where o.c_id=c.c_id AND o.s_id=s.s_id  $type";
        return  $db->select($Query,null);
    }
    public function TotalRevenueinLatestYear ($db)
    {
        $Query="SELECT (SUM(service.price)*20/100) as money FROM `ordering` , `service`
               where service.s_id=ordering.s_id AND ordering.status=1 AND ordering.date BETWEEN '2017-01-01'AND '2017-12-31'";
        return  $db->select($Query,null);
    }
    // order contact msgs
    public function sendcontact($message)
    {
        $this->order_contact->sendcontact($message);
    }
    public function reciveallcontact()
    {
        $this->order_contact->reciveallcontact();
    }
    // compeleteOrder function
    public function compeleteOrder ($order_id , $db)
    {
      // Get the ID of Service Provider & service price
      $sql = "SELECT customer.`c_id`,customer.`coins`, service.`price`  FROM `customer`,`service`,`ordering`
      where ordering.s_id = service.s_id and service.c_id = customer.c_id and ordering.order_id=?";
      $Attibutes= array
      ( $order_id );
      $data = $db->select($sql,$Attibutes);
      // Update Coins of Service Provider
      $coins = $data[0]['price']*(80/100)+$data[0]['coins'];
      $sql = "UPDATE `customer` SET `coins`= ? WHERE `c_id`=?";
      $Attibutes= array
      ( $coins ,$data[0]['c_id']);
      $db->update($sql,$Attibutes);
      // Update status of order
      $sql = "UPDATE `ordering` SET `status`=1 WHERE `order_id`=?";
      $Attibutes= array
      ( $order_id );
      return $db->update($sql,$Attibutes);
    }
     public function getOrderStatus($order_id , $db)
    {
      $sql = "SELECT `status` FROM `ordering` WHERE `order_id`=?";
      $Attibutes= array
      ( $order_id );
      $status = $db->select($sql,$Attibutes);
      return $status[0]['status'];
    }



}
