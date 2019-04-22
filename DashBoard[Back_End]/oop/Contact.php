<?php

abstract class Contact {
   // contact Attibutes
    protected $date;
    protected  $Message;
    protected $status;
    protected  $subject;
    protected  $to;
    
    // geter & setter Methods
    function getDate() {
        return $this->date;
    }

    function getMessage() {
        return $this->Message;
    }

    function getStatus() {
        return $this->status;
    }

    function setDate() {
        $date = date('Y-m-d H:i:s');
        $this->date = $date;
    }

    function setMessage($Message) {
        if(empty ($Message) ||ctype_space($Message) ) {return 'message can\'t be empty ';}
        elseif(!filter_var($Message, FILTER_SANITIZE_STRING)){return 'please enter valid message';}
        else
        {$this->Message = $Message; return true;}
    }

    function setStatus($status) {
        $this->status = $status;
    }
    function getSubject() {
        return $this->subject;
    }

    function setSubject($subject) {
        if(empty ($subject) ||ctype_space($subject) ) {return 'subject can\'t be empty ';}
        elseif(!filter_var($subject, FILTER_SANITIZE_STRING)){return 'please enter valid subject';}
        else {$this->subject = $subject; return true;}
    }
    function getTo() {
        return $this->to;
    }

    public abstract function setTo($to , $db);





}
