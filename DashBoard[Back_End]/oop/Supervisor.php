<?php

class Supervisor extends Employee implements Observer {
    /* Supervisor Constructor */
    function __construct()
    {

    }
     public function __wakeup()
    {
        $this->connect();
    }
     private function connect()
    {
        $this->db_connection = new Database();
    }

    /* class Methods */

    public function viewReport (Message $m ,$reciever , $type)
    {
        $reciever = $this->getID();
        return  $m->viewReport($reciever,$this->db_connection , $type);
    }
   public function addSupervisor ($img_flag)
    {
            $image = (($img_flag==0)?"default.png":$this->getPhoto_name());
            $this->connect();
            $sql = 'INSERT INTO manager (`name` , `E-mail` , `password`,`image`,`hours`,`salary`,`date`) VALUES (?,?,?,?,?,?,?)';
            $attributes = array
            (

                $this->getName(),
                $this->getE_mail(),
                $this->getPassword(),
                $image,
                $this->getHours(),
                $this->getSalary(),
                $this->getHireDate()
            );
           return $this->db_connection->insert($sql,$attributes);
   }
    public function view_specific_supervisor($id)
    {
        $this->connect();
        $sql='SELECT * FROM manager WHERE `mgr_id`= ?';
        $attribute = array($id);
        $data = $this->db_connection->select($sql,$attribute);

        $this->setName($data[0]["name"]);
        $this->setE_mail($data[0]["E-mail"]);
        $this->setPassword($data[0]["password"]);
        $this->setImage($data[0]["image"]);
        $this->setGroupID($data[0]["group_id"]);
        $this->setHours($data[0]["hours"]);
        $this->setSalary($data[0]["salary"]);
        $this->setHireDate($data[0]["date"]);
    }
    public function removeSupervisor ($ID)
    {
            $this->connect();
            $Query="DELETE FROM manager WHERE `mgr_id` = ?";
            $Attibutes = array($ID);
            return $this->db_connection->delete($Query,$Attibutes);
    }
    public function searchForSupervisor($search_key_word)
    {
            $this->connect();
            $Query="SELECT * FROM manager WHERE `group_id` = 1  AND ( mgr_id like '%$search_key_word%' or name like '%$search_key_word%' ) ";
            $Attibutes = NULL;
            return $this->db_connection->select($Query,$Attibutes);
    }
    public function updateSupervisor()
    {
        $this->connect();
        $Query="UPDATE manager SET salary=? , hours=? WHERE `group_id` = 1 AND `mgr_id`=".$this->getID()." ";
        $Attibutes=array
        (
            $this->getSalary() ,
            $this->getHours()
        );
        return $this->db_connection->update($Query,$Attibutes);
    }
    public function viewBestSupervisor ()
    {
        //code here
    }
    public function viewServiceRequest (CustomerService $cs)
    {
         //$cs = new [CustomerService()]
       // $cs->viewServiceRequest()
    }

   public function update ()
   {
      $this->connect();
      $sql = 'SELECT * FROM notification';
      $attribute = NULL;
      $check = $this->db_connection->select($sql,$attribute);
      return $check;
   }
   public function view_all_staff()
   {
      $this->connect();
      $sql = 'SELECT * FROM manager WHERE `group_id` = 1';
      $attribute = NULL;
      $check = $this->db_connection->select($sql,$attribute);
      return $check;
   }
   // getEmails of Managers to set Messages
   public function Mail_staff_E_mail($mgr_mail , $myid)
   {
      $this->connect();
      $sql = "SELECT `E-mail` FROM manager  WHERE `E-mail` like '%$mgr_mail%' AND `mgr_id`!= $myid";
      $attribute = NULL;
      $check = $this->db_connection->select($sql,$attribute);
      return $check;
   }

   // Inheritence function
    public function viewServices(Services $service,$id , $limit=0 , $type="All")
    {
        $this->connect();
        return $service->viewOwnerServices($this->db_connection,$id , $limit , $type);
    }

    public function updateServiceStatus(Services $service, CustomerService $customerservice){
        $this->connect();
        return $service->updateServiceStatus($this->db_connection,$this->ID,$customerservice);
    }

    public function showSelectedServices(Services $service){
        $this->connect();
        $value = $service->showSelectedServices($this->db_connection);
        $data=$value[0];
        if($data){
            $service->setService_name($data['name']);
            $service->setService_view($data['view']);
            $service->setStatus($data['status']);
            $service->setTags($data['tags']);
            $service->setPrice($data['price']);
            $service->setDeliver_date($data['d_date']);
            $service->setService_date($data['s_date']);
            $service->setDescription($data['description']);
            $service->setImages($this->db_connection);
            $service->setType($data['cat_id'],$this->db_connection);

            $client = new Client();
            $service->setOwnerID($data['c_id'], $client);
            return $client;
        }
    }

    public function searchForServices(Services $service,$keyword)
    {
        $this->connect();
        return $service->searchForServices($keyword,$this->db_connection,$this->getID());
    }
    // view supervisor category
    public function view_myCategory ($category)
    {
        $this->connect();
        return $category->view_supervisor_category($this->db_connection,$this->ID);
    }
    // view customers
    public function showCustomers ($client,$total="20" , $type)
    {
        $this->connect();
        return $client->getAllClients($total , $this->db_connection , $type);
    }
    // remove Customer
    public function removeCustomer ($client)
    {
        $this->connect();
        return $client->removeCustomer($this->db_connection);
    }
    // view Customer data
    public function getCustomerData($client)
    {
        $this->connect();
        return $client->getSpecificCustomer($this->db_connection);
    }
    //view Customer data
    public function getCustomerServices($client)
    {
        $this->connect();
        return $client->getSpecificCustomerservices($this->db_connection);
    }
    public function viewOrder($order , $type)
    {
        return $order->viewOrder($this->db_connection , $type);
    }
    /*****************DashboardFunctions**********************/

    public function DashboardMembers($system)
    {
        $data=  $system->DashboardMembers($this->db_connection);
        return $data[0];
    }
    //count services
    public function countServicesNum ($system ,  $type="All")
    {
        $data=  $system->countServicesNum($this->db_connection , $type);
        return $data[0];
    }
    //count Customer Services
    public function customerServiceCounter($message)
    {
        $data =  $message->countMessages($this->getID() , $this->db_connection);
        return $data[0];
    }

        //count Notification
    public function NotificationCounter($message)
    {
        $data =  $message->countNotification($this->getID() , $this->db_connection);
        return $data[0];
    }

     // read Notification
    public function readNotification($message)
    {
        return $message->readNotification($this->getID() , $this->db_connection);
    }

     public  function changeCustomerServices ($message_id)
    {
        $m = new CustomerService();
        return $m->changeMessageStatus($message_id, $this->db_connection);
    }
}
