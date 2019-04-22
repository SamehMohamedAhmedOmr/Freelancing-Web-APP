<?php

class Customer_Msg extends Contact implements Message {
    
      // override Method 

    public function readMessage($reciever,$sender,$db , $type , $kind=0) {
        $Query= "SELECT `m`.* , `c`.`c_id` , `c`.`c_name` , `c`.`image` 
                 from message m , customer c 
                 where `c`.`c_id` = `m`.`sender` and `m`.`reciever` = ?
                 and `m`.`date` in (SELECT MAX(`date`) FROM message
                 where `reciever` = ?
                 GROUP BY `sender` ORDER BY `date`)
                 ORDER by `m`.`date` DESC";
        $attributes = array($reciever,$reciever);
        return $db->select($Query,$attributes);
    }

    public function sendMessage($sender , $db , $kind=0){
        $Query= "INSERT INTO `message`(`msg`, `Reciver_Status`, `date`, `sender`, `reciever`)
                 VALUES (? , ? , now() , ? , ? ) ";
        $attributes = array($this->getMessage(), $this->getStatus() , $sender , $this->getTo());
        return $db->insert($Query,$attributes);
    }

   public function viewSpecificMessage($reciever, $sender,$date, $db , $kind=0) {
        $Query= "SELECT m.*  
                 from message m , customer c 
                 where  (m.`reciever` = ? or m.`reciever` = ? ) and c.`c_id` = m.`sender`  and ( m.`sender` = ? or m.`sender` = ?)
                 ORDER by m.`date`";
        $attributes = array($reciever,$sender,$sender,$reciever);
        return $db->select($Query,$attributes);
    }

    public function getContact($sender,$db){
        $sql = 'SELECT c.`c_id` , c.`c_name` , c.`image`
                FROM customer c
                where c.`c_id` = ?';
        $attributes = array($sender);
        return $db->select($sql,$attributes);
    }

    public function changeMessageStatus($newClient, $client , $kind=0) {
        $Query= "UPDATE message set `Reciver_Status` = ? where `reciever` = ? and `sender`= ? ";
        $attributes = array(1,$client->getID(),$newClient);
        return $client->db_connection->update($Query,$attributes);
    }

    public function countMessages($my_id, $db , $kind=0) {
        $Query= "SELECT Count(`msg`) as MsgNumber
                 from message
                 where `reciever` = ? and `Reciver_Status`= ?";
        $attributes = array($my_id,0);
        return $db->select($Query,$attributes);
    }
    
   public function setTo($to , $db) 
    {
        $query = "SELECT * FROM customer WHERE c_id = ? ";
        $attributes = array($to);
        $data = $db->select($query,$attributes);
        if($data!=0)
        {
            $this->to = $to;
            return true;
        }  
        else {return 'ID entered Not Found';}
    }

}
