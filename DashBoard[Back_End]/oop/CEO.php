<?php

class CEO extends Employee implements Sender {
    
     /* CEO Constructor */
    function __construct() {
        
    }    
     public function __wakeup()
    {
        $this->connect();
    }
    
     private function connect ()
    {
        $this->db_connection = new Database();
    }
      /* class Methods */   
    public function addSupervisor (Supervisor $s , $img_flag)
    {
        return $s->addSupervisor($img_flag);        
    }
     // instead of close account code here inside delete supervisor
    public function deleteSupervisor (Supervisor $s)
    {
        return $s->removeSupervisor($s->getID());
    }
    public function updateSupervisor (Supervisor $s)
    {
        return $s->updateSupervisor();
    }    
    // category methods
    public function viewMainCategory(Category $cat)
    {
      $this->connect();
      return $cat->viewMainCategory($this->db_connection);
    } 
    public function viewSubCategory(Category $category,$mgr_id)
    {
      $this->connect();
      return $category->viewsubCategory($this->db_connection,$mgr_id);
    }
    public function getSelectedCategory($id)
    {
      $this->connect();
      $category = new Category();
      return $category->getSelectedCategory($id,$this->db_connection);
    }

         
    public function addCategory($supervisor_id , Category $cat) 
    {
         $this->connect();
         return $cat->addCategory($supervisor_id , $this->db_connection);
    }

    public function editCategory($supervisorID, Category $cat) 
    {
      $this->connect();
      return $cat->editCategory($supervisorID,$this->db_connection);
    } 

    public function deleteCategory($cat_id) 
    {
      $this->connect();
      $cat = new Category();
      return $cat->deleteCategory($cat_id,$this->db_connection);
    } 

    public function deleteAllSubCategory($cat_id) 
    {
      $this->connect();
      $cat = new Category();
      return $cat->deleteAllSubCategory($cat_id,$this->db_connection);
    }  
         
   
    /*override Methods */   
   public function notifyObserver (Notification $N)
    {
        $sql='INSERT INTO `notification` (`subject`, `notification`, `date`, `mgr_id`)
              VALUES (?,?,?,?)';
        $attributes = array(
          $N->getSubject(),
          $N->getMessage(),
          $N->getDate(),
          $this->getID()
        );
        $data = $this->db_connection->insert($sql,$attributes);
        if($data==1){
          $sql='UPDATE `manager` m
                SET `m`.`NotificationNumber` = `m`.`NotificationNumber`+ ?
                 WHERE `m`.`group_id` = ?';
          $attributes = array(1,1);
          return $this->db_connection->update($sql,$attributes);
        }

    }
    public function view_all_staff(Supervisor $s)
    {
        $data = $s->view_all_staff();
        return $data;
    }
    
    public function get_supervisor (Supervisor $s)
    {
        $s->view_specific_supervisor($s->getID());
        return $s;
    }
    public function searchForSupervisor(Supervisor $s , $search_key_word)
    {
       return $s->searchForSupervisor($search_key_word);
    }
    /* Inherint Method */
    public function viewServices(Services $service,$id)
    {
       return null;
    }
    public function CustomerStatistics ($system , $from , $to)
    {
         return $system->countMemberStatistics($this->db_connection , $from , $to);
    }
    /**********DashBoard Functions**********/
    public function CountSupervisors($system)
    {
        $data =  $system->CountSupervisors($this->db_connection);
        return $data[0];
    }
    // count category
    public function countCategory ($system)
    {
        $data=  $system->countCategory($this->db_connection);
        return $data[0];
    }
    // count revenue per month in the latest year
    public function TotalRevenueinLatestYear ($order)
    {
        $data = $order->TotalRevenueinLatestYear($this->db_connection);
        return $data[0];
    }
    // service in each category statistics
    public function serviceStatistics ($service , $from='2017-01-01' , $to='2017-12-31')
    {
        $data = $service->serviceStatistics($this->db_connection , $from , $to);
        return $data;
    }
}
