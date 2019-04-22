<?php

class Category {
   // class attributes
    private $cat_id;
    private $cat_Name;
    private $cat_Description;
    private $visible;
    private $parent;
    private $ordering;
    // Constructor
    function __construct() {
        
    }
    // getter & setter
    function getCat_id() {
        return $this->cat_id;
    }

    function getCat_Name() {
        return $this->cat_Name;
    }

    function getCat_Description() {
        return $this->cat_Description;
    }

    function getVisible() {
        return $this->visible;
    }

    function getParent() {
        return $this->parent;
    }

    function getOrdering() {
        return $this->ordering;
    }

    function setCat_id($cat_id,$dbConn) {

         $cat_id = trim($cat_id);
         $cat_id = strip_tags($cat_id);
         $cat_id = filter_var($cat_id,FILTER_SANITIZE_NUMBER_INT);

            $sql = 'SELECT * FROM category WHERE `cat_id`=?';
            $attributes = array ($cat_id);
            if($dbConn->select($sql,$attributes)==0){return " this category is not Exist anymore";}
            else {$this->cat_id = $cat_id;}
    }

    function setCat_Name($cat_Name) 
    {
        $cat_Name = trim($cat_Name);
        $cat_Name = strip_tags($cat_Name);
        $cat_Name = filter_var($cat_Name,FILTER_SANITIZE_STRING);
        if(empty($cat_Name))
        { return "category name can't be empty";  }
        else
        {
            if(strlen($cat_Name)<3 || strlen($cat_Name)>30) 
            {return "category name must be <strong> more than 3 character and less than 30 char";}
            else {$this->cat_Name = $cat_Name;return NULL;}
        }
    }

    function setCat_Description($cat_Description) {
        $cat_Description = trim($cat_Description);
        $cat_Description = strip_tags($cat_Description);
        $cat_Description = filter_var($cat_Description,FILTER_SANITIZE_STRING);
        if(empty($cat_Description))
        { return "category description can't be empty "; }
        else
        {
            if(strlen($cat_Description)<12) 
            {return "pls provide more details about category";}
            else {$this->cat_Description = $cat_Description; return NULL;}
        }
    }

    function setVisible($visible) {
         $visible = trim($visible);
         $visible = strip_tags($visible);
         $visible = filter_var($visible,FILTER_SANITIZE_NUMBER_INT);
         $this->visible = $visible;
    }

    function setParent($parent,$dbConn=0,$validate=0) {
        if($validate==0)
        {
            $parent = trim($parent);
            $parent = strip_tags($parent);
            $parent = filter_var($parent,FILTER_SANITIZE_NUMBER_INT);
            $sql = 'SELECT * FROM category WHERE `cat_id`=?';
            $attributes = array ($parent);
            if($dbConn->select($sql,$attributes)==0){return "this category is not Exist anymore";}
            else {$this->parent = $parent;}
        }
        else{$this->parent = $parent;}
    }

    function setOrdering($ordering) 
    {
         $ordering = trim($ordering);
         $ordering = strip_tags($ordering);
         $ordering = filter_var($ordering,FILTER_SANITIZE_NUMBER_INT);
         if(empty($ordering))
         {return "order number can't be empty";}
         else
         {
            if(!intval($ordering))
            {return "pls enter correct Number ";}
            elseif($ordering<0 || $ordering >100)
            {return "order number must be between ( 1 - 100 )";}
            else {$this->ordering = $ordering; return null;}  
         }               
    }
   // class mmethods
        
    public function addCategory($supervisor_id , $db) 
    {
        $sql = "INSERT INTO category (`name`,`ordering`,`description`,`visible`,`parent`,`mgr_id`) VALUES (?,?,?,?,?,?)";
        $attributes = array 
        (
            $this->getCat_Name(),
            $this->getOrdering(),
            $this->getCat_Description(),
            $this->getVisible(),
            $this->getParent(),
            $supervisor_id
        );
        return $db->insert($sql,$attributes);
    } 
    
    public function editCategory($supervisorID,$db) 
    {
        $sql="UPDATE `category` SET `name`= ?,`ordering`= ?,`description`= ?,`visible`= ?,
                `parent`= ?,`mgr_id`= ? WHERE `cat_id`= ?";

        $attributes = array(
            $this->getCat_Name(),
            $this->getOrdering(),
            $this->getCat_Description(),
            $this->getVisible(),
            $this->getParent(),
            $supervisorID,  // mgr_id
            $this->getCat_id()
        );
        return $db->update($sql,$attributes);
    } 

    public function deleteCategory($cat_id,$db) 
    {
        $sql="DELETE FROM category where `cat_id` = ?";
        $attributes = array(
            $cat_id
        );
        return $db->delete($sql,$attributes);
    }

    public function deleteAllSubCategory($cat_id,$db) 
    {
        $sql="DELETE FROM category where `parent` = ?";
        $attributes = array(
            $cat_id
        );
        return $db->delete($sql,$attributes);
    }

    public function viewMainCategory($db){
        $sql='SELECT * FROM category where `parent` = 0 ';
        $attributes = null;
        return $db->select($sql,$attributes);
    }

    public function viewsubCategory($db,$mgr_id){
        $sql="SELECT * from `category` WHERE `parent` = ?";
        $attributes = array(
            $mgr_id
        );
        return $db->select($sql,$attributes);
    }

    public function getSelectedCategory($id,$db){
        $sql="SELECT * from `category` WHERE `cat_id` = ?";
        $attributes = array(
            $id
        );
        return $db->select($sql,$attributes);
    }
    function view_supervisor_category($db , $mgr_id)
    {
        $sql = 
        "SELECT category.* , COUNT(service.`s_id`) as `number_of_services` FROM `category` 
        LEFT JOIN `service`
        on service.`cat_id`=category.`cat_id`
        WHERE category.mgr_id= ? 
        GROUP BY category.name";
        $attributes = array ($mgr_id);
        return $db->select($sql,$attributes);    
    }
    function view_top_category ($db)
    {
        $sql = " select name ,cat_id , ordering from category order by ordering desc  LIMIT 4";
        return $db->select($sql,NULL);  
    }
  
}
