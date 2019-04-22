<?php


class CustomerService extends Contact implements Message {
    
    public $kind; 
    public $service_id;

    function getKind() {
        return $this->kind;
    }

    function setKind($kind) {
        $this->kind = $kind;
    }

    function setService_id($service_id) {
        if(empty ($service_id) ||ctype_space($service_id) ) {return 'Service can\'t be empty ';}
        elseif(!filter_var($service_id, FILTER_VALIDATE_INT)){return 'ERROR IN CUSTOMER SERVICE';}
        else
        {$this->service_id = $service_id; return true;}
    }

    function getService_id() {
        return $this->service_id;
    }

    public function setTo($to , $db) 
    {
       if(!filter_var($to, FILTER_SANITIZE_EMAIL)){return 'please enter valid E-mail';}
       else
       {
            $query = "SELECT * FROM `customer` WHERE `E-mail` = ? ";
            $attributes = array($to);
            $value = $db->select($query,$attributes);
            $data = $value[0];
            if(isset($data))
            {
                $id=$data['c_id'];
                $this->to = $id;
                return true;
            }  
            else {return 'E_mail you have entered Not Found';}
        }  
    }

    // Class Methods
   
     public function viewReport($reciever,$db , $type)
    {
        // change between options view inbox & send inbox
        if($type=="All")
        {
               $Query = 
                       " SELECT `customerservice`.* , `customer`.`c_name` as senderName
                                , `s`.`name` as serviceName
                         FROM `customerservice` , `customer`, `service` s
                         WHERE `customerservice`.`c_id`= `customer`.`c_id`
                         And `s`.`s_id` =  `customerservice`.`service_id`
                         And `customerservice`.`Type`=5
                         AND `customerservice`.`mgr_id` = ? order by `date` desc "; 
                $attributes= array ($reciever);                   
            // execute & return output
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
            return $data;
            }  
            else {return NULL;}
        }
        // inbox & send-box search bars
        else
        {
               $Query = 
                       " SELECT `customerservice`.* , `customer`.`c_name` as senderName
                                , `s`.`name` as serviceName
                         FROM `customerservice` , `customer`, `service` s
                         WHERE `customerservice`.`c_id`= `customer`.`c_id`
                         And `s`.`s_id` =  `customerservice`.`service_id`
                         And `customerservice`.`Type`=5
                         AND ( `customerservice`.`subject` like '%$type%' or `customer`.`c_name` like '%$type%' or `customer`.`E-mail` like '%$type%' )
                         AND `customerservice`.`mgr_id` = ? order by `date` desc "; 
                 $attributes= array ($reciever);            
        }
        
        // execute & return output
        $data = $db->select($Query,$attributes);
        if($data!=0)
        {
        return $data;
        }  
        else {return NULL;}  
    }
    
    public function viewMyReports($reciever,$db , $type)
    {
        // change between options view inbox & send inbox
        if($type=="All")
        {
               $Query = 
                       " SELECT `customerservice`.* , `customer`.`c_name` as senderName
                                , `s`.`name` as serviceName
                         FROM `customerservice` , `customer`, `service` s
                         WHERE `customerservice`.`c_id`= `customer`.`c_id`
                         And `s`.`s_id` =  `customerservice`.`service_id`
                         And `customerservice`.`Type`=5
                         AND `customerservice`.`c_id` = ? order by `date` desc "; 
                $attributes= array ($reciever);                   
            // execute & return output
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
            return $data;
            }  
            else {return NULL;}
        }
        // inbox & send-box search bars
        else
        {
               $Query = 
                       " SELECT `customerservice`.* , `customer`.`c_name` as senderName
                                , `s`.`name` as serviceName
                         FROM `customerservice` , `customer`, `service` s
                         WHERE `customerservice`.`c_id`= `customer`.`c_id`
                         And `s`.`s_id` =  `customerservice`.`service_id`
                         And `customerservice`.`Type`=5
                         AND ( `customerservice`.`subject` like '%$type%')
                         AND `customerservice`.`c_id` = ? order by `date` desc "; 
                 $attributes= array ($reciever);            
        }
        
        // execute & return output
        $data = $db->select($Query,$attributes);
        if($data!=0)
        {
        return $data;
        }  
        else {return NULL;}  
    }
    
    public function acceptServiceRequest ($sender , $db)
    { 

        $Query= "INSERT INTO customerservice (`subject`, `content`, `type`, `date`, `status`, 
        `mgr_id`, `c_id`, `service_id`) VALUES (?,?,?,now(),?,?,?,?) ";

        $attributes = array($this->getSubject(),$this->getMessage(),1,$this->getStatus(),$sender, $this->getTo(),$this->getService_id());

        return $db->insert($Query,$attributes);
    }
    
    public function rejectServiceRequest ($sender , $db)
    {  

        $Query= "INSERT INTO customerservice (`subject`, `content`, `type`, `date`, `status`, 
        `mgr_id`, `c_id`, `service_id`) VALUES (?,?,?,now(),?,?,?,?) ";

        $attributes = array($this->getSubject(),$this->getMessage(),2,$this->getStatus(),$sender, $this->getTo(),$this->getService_id());

        return $db->insert($Query,$attributes);
    }
    
    // override Method
        // override Method
    public function readMessage($reciever,$sender,$db,$type,$kind=0) 
    {
        if($kind==1){
            return $this->readCustomerMessage($reciever,$sender,$db,$type);
        }
        else{
            // change between options view inbox & send inbox
            if($type=="All")
            {
                // [view option ]
                if($sender==NULL)
                {
                   $Query = 
                           " SELECT `customerservice`.* , `customer`.`c_name` as senderName 
                             FROM `customerservice` , `customer`
                             WHERE `customerservice`.`c_id`= `customer`.`c_id` 
                             And `customerservice`.`Type`=?
                             AND `mgr_id` = ? order by `date` desc "; 
                    $attributes= array ($this->getKind(), $reciever);                   
                }
                else // [send inbox option ]
                {
                    $Query = 
                           " SELECT `customerservice`.* , `customer`.`c_name` as `to`
                             FROM `customerservice` , `customer` 
                             WHERE `customerservice`.`c_id`= `customer`.`c_id` 
                             And `customerservice`.`Type`=?
                             AND `mgr_id` = ? order by `date` desc "; 
                    $attributes= array ($this->getKind(),$sender);            
                }
                // execute & return output
                $data = $db->select($Query,$attributes);
                if($data!=0)
                {
                return $data;
                }  
                else {return NULL;}
            }
            // inbox & send-box search bars
            else
            {
                if($sender==NULL)  // [view option  search ]
                {

                    $Query = 
                           " SELECT `customerservice`.* , `customer`.`c_name` as senderName 
                             FROM `customerservice` , `customer`
                             WHERE `customerservice`.`c_id`= `customer`.`c_id` 
                             And `customerservice`.`Type`=?
                             AND `mgr_id` = ?
                             AND ( `customerservice`.`subject` like '%$type%' or `customer`.`c_name` like '%$type%' or `customer`.`E-mail` like '%$type%' )
                             order by `date` desc "; 
                             
                     $attributes= array ($this->getKind(),$reciever);    
                   
                }
                else // [view send box option ]
                {

                    $Query = 
                           " SELECT `customerservice`.* , `customer`.`c_name` as `to` 
                             FROM `customerservice` , `customer`
                             WHERE `customerservice`.`c_id`= `customer`.`c_id` 
                             And `customerservice`.`Type`=?
                             AND `mgr_id` = ?
                             AND ( `customerservice`.`subject` like '%$type%' or `customer`.`c_name` like '%$type%' or `customer`.`E-mail` like '%$type%' )
                             order by `date` desc ";

                    $attributes= array ($this->getKind(),$sender);     
                }           
            }
            
            // execute & return output
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
            return $data;
            }  
            else {return NULL;}   
        }  
    }
    
    public function sendMessage($sender , $db,$kind=0) 
    {
        if($kind==0){ // access by supervisor
            $Query = "INSERT INTO customerservice
                 (`subject`, `content`, `type`, `date`, `status`, `mgr_id`, `c_id`, `service_id`)
                 VALUES (?,?,?,now(),?,?,?,?) ";

            $attributes = array($this->getSubject(),$this->getMessage(),4,$this->getStatus(),$sender, $this->getTo(),$this->getService_id());

            return $db->insert($Query,$attributes);
        }
        elseif ($kind==1) { // access by Customer
            
        }
    }

    public function viewSpecificMessage($reciever, $sender,$date, $db,$kind=0) 
    {
        if ($kind == 0) {
            $Query = 
                    "SELECT `customerservice`.* , customer.`c_name` as `sender` , 
                             customer.`E-mail` as senderMail ,
                             manager.`E-mail` as receiverMail  ,
                             manager.`name` as reciever  
                        FROM `customerservice` , `customer` customer , `manager` manager
                        WHERE manager.`mgr_id` = customerservice.`mgr_id` 
                        AND  customer.`c_id`= customerservice.`c_id`
                        AND customerservice.`date` = ? 
                        AND customerservice.`mgr_id` = ?
                        AND customerservice.`c_id` = ?";
                        
            $attributes= array ($date,$reciever,$sender); 
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
                return $data;
            }  
            else {return NULL;}
        }
        elseif($kind==1){
            $Query = 
                    "SELECT `customerservice`.* , manager.`name` as `sender` ,
                             manager.`E-mail` as senderMail ,
                             customer.`E-mail` as receiverMail  ,
                             customer.`c_name` as reciever  
                        FROM `customerservice` , `customer` customer , `manager` manager
                        WHERE manager.`mgr_id` = customerservice.`mgr_id` 
                        AND  customer.`c_id`= customerservice.`c_id`
                        AND customerservice.`date` = ? 
                        AND customerservice.`mgr_id` = ?
                        AND customerservice.`c_id` = ?";
                        
            $attributes= array ($date,$sender,$reciever); 
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
                return $data;
            }  
            else {return NULL;}
        }
    }


    public function changeMessageStatus($message_id, $db,$kind=0) 
    {
        $keys= explode('*', $message_id);
        $msg_sender = $keys[0];
        $msg_reciever = $keys[1];
        $date = $keys[2]; 
        $Query = "UPDATE `customerservice`
                  SET `status`=? 
                  WHERE `date` = ? AND `c_id` = ? AND  `mgr_id` =? ";
        $attributes =  array (1,$date,$msg_sender,$msg_reciever);
        if($kind==0){
            return $db->update($Query,$attributes); 
        }
        elseif ($kind==1) {
            return $db->db_connection->update($Query,$attributes); 
        }
    }

    public function countMessages($my_id, $db,$kind=0) 
    {
        if ($kind==0) {
            $Query = " SELECT COUNT(*) as `C_SNumbers` FROM `customerservice` WHERE `mgr_id` = ? AND status =0 ";
            $attributes =  array ($my_id);
            return $db->select($Query,$attributes);  
        }
        elseif($kind==1){
            $Query = " SELECT COUNT(*) as `myMessageNumber` FROM `customerservice` WHERE `c_id` = ? AND status = 0 ";
            $attributes =  array ($my_id);
            return $db->select($Query,$attributes); 
        }      
    }

    public function readCustomerMessage($reciever,$sender,$db,$type)
    { // change between options view inbox & send inbox
            if($type=="All")
            {
                // [view option ]
                if($sender==NULL)
                {
                   $Query = 
                           " SELECT `customerservice`.* , `manager`.`name` as senderName 
                             FROM `customerservice` , `manager`
                             WHERE `customerservice`.`mgr_id`= `manager`.`mgr_id` 
                             And `customerservice`.`Type`=?
                             AND `customerservice`.`c_id` = ? order by `date` desc "; 
                    $attributes= array ($this->getKind(), $reciever);                   
                }
                else // [send inbox option ]
                {
                    $Query = 
                           " SELECT `customerservice`.* , `manager`.`name` as `to`
                             FROM `customerservice` , `manager` 
                             WHERE `customerservice`.`mgr_id`= `manager`.`mgr_id` 
                             And `customerservice`.`Type`=?
                             AND `customerservice`.`c_id` = ? order by `date` desc "; 
                    $attributes= array ($this->getKind(),$sender);            
                }
                // execute & return output
                $data = $db->select($Query,$attributes);
                if($data!=0)
                {
                return $data;
                }  
                else {return NULL;}
            }
            // inbox & send-box search bars
            else
            {
                if($sender==NULL)  // [view option  search ]
                {

                    $Query = 
                           " SELECT `customerservice`.* , `manager`.`name` as senderName 
                             FROM `customerservice` , `manager`
                             WHERE `customerservice`.`mgr_id`= `manager`.`mgr_id` 
                             And `customerservice`.`Type`=?
                             AND `customerservice`.`c_id` = ?
                             AND ( `customerservice`.`subject` like '%$type%' )
                             order by `date` desc "; 
                             
                     $attributes= array ($this->getKind(),$reciever);    
                   
                }
                else // [view send box option ]
                {

                    $Query = 
                           " SELECT `customerservice`.* , `manager`.`name` as `to` 
                             FROM `customerservice` , `manager`
                             WHERE `customerservice`.`mgr_id`= `manager`.`mgr_id` 
                             And `customerservice`.`Type`=?
                             AND `customerservice`.`c_id` = ?
                             AND ( `customerservice`.`subject` like '%$type%')
                             order by `date` desc ";

                    $attributes= array ($this->getKind(),$sender);     
                }           
            }
            
            // execute & return output
            $data = $db->select($Query,$attributes);
            if($data!=0)
            {
            return $data;
            }  
            else {return NULL;}  
    }
}
