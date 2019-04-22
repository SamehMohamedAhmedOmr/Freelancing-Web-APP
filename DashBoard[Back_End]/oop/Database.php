<?php

class Database {
     public $db_conn;
    //connect to database
    public function __construct ()
    {
        $this->db_conn = dbConfig::getInstance();
        return $this->db_conn;
    }
    //disconnect to database
    public function disconnect ()
    {
        $this->db_conn = NULL;
    }

    public function select($sql,$attribute)
    {
        $val=$this->db_conn->prepare($sql);
        $val->execute($attribute);
        $count = $val->rowCount();
        if($count>0)
        {
            return $val->fetchAll();
        }
        else
        {return 0;}
    }

    public function insert($sql,$attribute){
        try {
        $val=$this->db_conn->prepare($sql);
        $val->execute($attribute);
        return 1;
        } catch (PDOException $ex) {return 0;}
    }

    public function update($sql,$attribute){
        try{
        $val=$this->db_conn->prepare($sql);
        $val->execute($attribute);
        return 1;
        }
        catch(PDOException $ex){return 0;}
    }
    
    public function delete($sql,$attribute){
        try{
            $val=$this->db_conn->prepare($sql);
            $val->execute($attribute);
            return 1;
        }
        catch(PDOException $ex){return $ex;}
    }

}
