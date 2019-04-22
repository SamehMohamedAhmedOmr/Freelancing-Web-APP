<?php

//require_once '../config/includes.php';
class Manager_Msg extends Contact implements Message {
    
    public function setTo($to , $db) 
    {
       if(!filter_var($to, FILTER_SANITIZE_EMAIL)){return 'please enter valid E-mail';}
       else
       {
            $query = "SELECT * FROM `manager` WHERE `E-mail` = ? ";
            $attributes = array($to);
            $data = $db->select($query,$attributes);
            if($data!=0)
            {
                $id=$data[0]['mgr_id'];
                $this->to = $id;
                return true;
            }  
            else {return 'E_mail you have entered Not Found';}
        }
        
    }
    // override Method
    public function readMessage($reciever,$sender,$db , $type , $kind=0) 
    {
        // change between options view inbox & send inbox
        if($type=="All")
        {
            // [view option ]
            if($sender==NULL)
            {
               $Query = 
                       " SELECT `manager_msg`.* , name as senderName "
                            . "FROM `manager_msg` , `manager` "
                             . "WHERE `mgr_id`= `mgr_sender`  AND `mgr_reciever` = ? order by `date` desc"; 
                $attributes= array ( $reciever);                   
            }
            else // [send inbox option ]
            {
                $Query = 
                       " SELECT `manager_msg`.* , name as `to` "
                            . " FROM manager_msg , manager "
                             . " WHERE mgr_id=mgr_reciever AND  mgr_sender=? order by `date` desc";  
                $attributes= array ($sender);            
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
                   " SELECT `manager_msg`.* , name as senderName "
                    . "FROM `manager_msg` , `manager` "
                    . "WHERE `mgr_id`= `mgr_sender`  AND `mgr_reciever` = ? AND `manager`.`E-mail` like '%$type%' order by `date` desc "; 
                 $attributes= array ($reciever);    
               
            }
            else // [view send box option ]
            {
                $Query = 
                       " SELECT `manager_msg`.* , name as `to` "
                            . " FROM manager_msg , manager "
                            . " WHERE mgr_id=mgr_reciever AND  mgr_sender=? AND `manager`.`E-mail` like '%$type%' order by `date` desc ";  
                $attributes= array ($sender);     
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
    
    public function sendMessage($sender , $db , $kind=0) 
    {
        $Query= "INSERT INTO `manager_msg` (`message`,`subject`,`status`,`date`,`mgr_sender`,`mgr_reciever`) VALUES (? , ? , ? , now() , ? , ? ) ";
        $attributes = array($this->getMessage(), $this->getSubject(), $this->getStatus(),$sender, $this->getTo());
        return $db->insert($Query,$attributes);
    }

    public function viewSpecificMessage($reciever, $sender,$date, $db , $kind=0) 
    {
        $Query = 
                "SELECT `manager_msg`.* , s.name as `sender` , s.`E-mail` as senderMail , r.`E-mail` as receiverMail  , r.name as `reciever` "
                    ." FROM manager_msg , manager s , manager r "
                    ." WHERE r.mgr_id=mgr_reciever AND  s.mgr_id=mgr_sender AND manager_msg.date = ?  AND mgr_reciever = ? AND mgr_sender =?";  
        $attributes= array ($date,$reciever,$sender,); 
        $data = $db->select($Query,$attributes);
        if($data!=0)
        {
            return $data;
        }  
        else {return NULL;}
    }

    public function changeMessageStatus($message_id, $db , $kind=0) 
    {
        $keys= explode('*', $message_id);
        $msg_sender = $keys[0];
        $msg_reciever = $keys[1];
        $date = $keys[2];        
        $Query = "UPDATE `manager_msg` SET status=? WHERE `date` = ? AND `mgr_sender` = ? AND  `mgr_reciever` =? ";
        $attributes =  array (1,$date,$msg_sender,$msg_reciever);
        $db->update($Query,$attributes);
        
    }
    // count Messages
    public function countMessages($my_id, $db , $kind=0) {
        $Query = " SELECT COUNT(*) as MailBoxNum FROM `manager_msg` WHERE `mgr_reciever` = ? AND status =0 ";
        $attributes =  array ($my_id);
        return $db->select($Query,$attributes);
    }

}
