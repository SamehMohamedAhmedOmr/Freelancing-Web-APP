<?php

class ShoppingCart {
    //cart attributes
    private $date_of_shopping;
    //Constructor
    function __construct() {

    }
    //getter & setter
    function getDate_of_shopping() {
        return $this->date_of_shopping;
    }

    function setDate_of_shopping($date_of_shopping) {
        $this->date_of_shopping = $date_of_shopping;
    }

    function getCart_services() {
        return $this->date_of_shopping;
    }

    function setCart_services($cart_services) {
        $this->$cart_services = $cart_services;
    }

    // cart Methods

    public function AddtoCart_cookie($s)
    {
        $date = date("Y-m-d");
        $db_connection = new Database();
        $sql = "SELECT service.name , service.price , service.status
                FROM `service`
                WHERE service.s_id = $s";
        $serviceData = $db_connection->select($sql,null);
        $serviceData = $serviceData[0];
        $servicesInShppingCart;

        if(isset($_COOKIE['ShoppingCart']))
        {
          $servicesInShppingCart = unserialize($_COOKIE['ShoppingCart']);
          if(sizeof($servicesInShppingCart) !=0)
            $servicesInShppingCart[]=array('date'=>$date,'s_id'=>$s,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']);
          else
            $servicesInShppingCart=array(array('date'=>$date,'s_id'=>$s,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']));
        }
        else {
          $servicesInShppingCart=array(array('date'=>$date,'s_id'=>$s,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']));
        }
        setcookie ("ShoppingCart",serialize($servicesInShppingCart),time()+ 86400*30,"/"); // 1 day
    }

    public function AddtoCart($c , $s , $date ,$db_connection)
    {
        if($c != -1)
        {
          $sql="INSERT INTO `shopping` (`c_id`, `s_id`, `date`) VALUES (?, ?, ?);";
          $Attibutes=array
          ($c,$s->getService_id(),$date);
          return $db_connection->insert($sql,$Attibutes);
        }
        else
        {
          $s_id = $s->getService_id();
             $sql = "SELECT service.name , service.price , service.status
                     FROM `service`
                     WHERE service.s_id = $s_id";
             $serviceData = $db_connection->select($sql,null);
             $serviceData = $serviceData[0];
             $servicesInShppingCart;
             if(isset($_COOKIE['ShoppingCart']))
             {
                 $servicesInShppingCart = unserialize($_COOKIE['ShoppingCart']);
                 if(sizeof($servicesInShppingCart) !=0)
                   $servicesInShppingCart[]=array('date'=>$date,'s_id'=>$s_id,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']);
                 else
                   $servicesInShppingCart=array(array('date'=>$date,'s_id'=>$s_id,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']));
             }
             else
             {
               $servicesInShppingCart=array(array('date'=>$date,'s_id'=>$s_id,'name'=>$serviceData['name'],'price'=>$serviceData['price'],'status'=>$serviceData['status']));
             }
             setcookie ("ShoppingCart",serialize($servicesInShppingCart),time()+ 86400*30,"/"); // 1 day

             return 1;
        }
    }

    public function deleteFromCart($s ,$c ,$db_connection)
    {
      $s_id = $s->getService_id();
      $sql="DELETE FROM `shopping` WHERE c_id=$c and s_id=$s_id";
      return $db_connection->delete($sql,null);
    }

    public function viewCart($c_ID,$db_connection)
    {
        $sql = "SELECT shopping.date , service.s_id , service.name , service.price , service.status , customer.c_name , customer.c_id
                FROM `shopping`,`service`,`customer`
                WHERE service.s_id = shopping.s_id and service.c_id = customer.c_id and shopping.c_id = ?";
        $Attibutes=array
        ($c_ID);
        return $db_connection->select($sql,$Attibutes);
    }

    public function placeServiceToOrder(Order $o, Client $c, Services $s)
    {
      $s_id = $s->getService_id();
      $c->connect();
      $o->placeOrder($c->getID(), $s_id, $c->db_connection);
      echo $this->deleteFromCart($s_id,$c->getID(),$c->db_connection);
    }
    public function calcTotalPrice($c_ID,$db_connection)
    {
      $sql = "SELECT SUM(price) FROM `shopping`,`service` WHERE service.s_id = shopping.s_id and shopping.c_id = ?";
      $Attibutes=array
      ($c_ID);
      return $db_connection->select($sql,$Attibutes);
    }

        public function getCount($c,$db_connection)
    {
      if($c != -1)
        {
          $sql = "SELECT count(*) as `count` FROM `shopping` WHERE shopping.c_id = ?";
          $Attibutes=array ($c);
          $count = $db_connection->select($sql,$Attibutes);
          $count=$count[0]['count'];
          echo $count;
        }
      else {
        if(isset($_COOKIE['ShoppingCart']))
        {
            $servicesInShppingCart = unserialize($_COOKIE['ShoppingCart']);
            echo sizeof($servicesInShppingCart);
        }
        }
    }
}
