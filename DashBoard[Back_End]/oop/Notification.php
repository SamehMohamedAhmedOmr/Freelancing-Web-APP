<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notification
 *
 * @author kinghh19
 */
class Notification extends Contact {
    
    public  function Notification ()
    {
        // code here
    }
    public function setTo($to , $db) 
    {
        
    }
        // count Notification
    public function countNotification($my_id, $db) {
        $Query = "SELECT `NotificationNumber` from  manager where mgr_id = ?";
        $attributes =  array ($my_id);
        return $db->select($Query,$attributes);
    }

        // read Notification
    public function readNotification($my_id, $db) {
        $sql='UPDATE `manager` m
              SET `m`.`NotificationNumber` = ?
              WHERE `m`.`mgr_id` = ?';
          $attributes = array(0,$my_id);
          return $db->update($sql,$attributes);
    }
    
}
