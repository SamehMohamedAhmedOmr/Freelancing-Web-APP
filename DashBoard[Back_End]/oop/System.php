<?php

class System {
     /* System Attributes */
    private $Name;
    private $Tel = array ();
    private $fax = array ();
    private $E_mail = array();
    private $Tax;
    /* System Constructor */
    function __construct() {
        
    }
    /*getter & setter methods*/
    function getName() {
        return $this->Name;
    }

    function getTel() {
        return $this->Tel;
    }

    function getFax() {
        return $this->fax;
    }

    function getE_mail() {
        return $this->E_mail;
    }

    function setName($Name) {
        $this->Name = $Name;
    }

    function setTel($Tel) {
        $this->Tel = $Tel;
    }

    function setFax($fax) {
        $this->fax = $fax;
    }

    function setE_mail($E_mail) {
        $this->E_mail = $E_mail;
    }
    function getTax() {
        return $this->Tax;
    }

  

        /* class Methods */   
    
    public function setTaxValue($Tax) {
    //$this->Tax = $Tax;
    }
    public function gettelphone($db)
    {
       $Query="select * from system";
       return  $db->select($Query,null); 
    }
    //get system E-mails
    public function getmails($db)
    {
        $Query="select * from system_mails";
        return  $db->select($Query,null);     
    }
    // get getadresses
   public function  getadresses($db)
   {
        $Query="select * from system_adress";
        return  $db->select($Query,null);    
   }
   // get system - faxes
  public function  getfaxes($db)
   {
        $Query="select * from system_faxes";
        return  $db->select($Query,null);    
   }
   //  delete addresses
   public function deleteadress($id,$db)
   {
        $Query="delete from system_adress where id=$id ";
       return  $db->delete($Query,null);
   }
   // deletemails
    public function deletemails($id,$db)
    {
        $Query="delete from system_mails where id=$id ";
        return  $db->delete($Query,null); 
    }
    //delete phones
    public function deletephones($id,$db)
    {
        $Query="delete from system where id=$id ";
        return  $db->delete($Query,null);   
    }
    //delete faxes
    public function deletefaxes($id,$db)
    {
        $Query="delete from system_faxes where id=$id ";
        return  $db->delete($Query,null);
    }
    // insert phones
    public function insertphone($add,$db)
    {
        $Query="INSERT INTO system (`phone_numbers`) VALUES (?) ";
        $Attributes=array($add);
        return $db->insert($Query,$Attributes);    
    }
    // isnert Mails
    public function insertmail($add,$db)
    {
        $Query="INSERT INTO system_mails (`emails`) VALUES (?) ";
        $Attributes=array($add);
        return $db->insert($Query,$Attributes);    
    }
   // insert adress
    public function insertadress($add,$db)
    {
        $Query="INSERT INTO system_adress (`adresses`) VALUES (?) ";
        $Attributes=array($add);
        return $db->insert($Query,$Attributes);    
    }
    // insert fax
    public function insertfax($add,$db)
    {
        $Query="INSERT INTO system_faxes (`faxes`) VALUES (?) ";
        $Attributes=array($add);
        return $db->insert($Query,$Attributes);    
    }
    /********************Dash Board Functions************************/
    public function DashboardMembers ($db)
    {
        $Query="
                SELECT (SUM(CASE WHEN customer.regstatus = 0 THEN 1 ELSE 0 END)) as NonActivated
                , (SUM( CASE WHEN customer.regstatus = 1 THEN 1 ELSE 0 END)) as Activated
                , COUNT(*) as AllCustomers
                FROM customer"
                ;
        $Attributes=array();
        return $db->select($Query,$Attributes);
    }
    public function countServicesNum ($db , $type )
    {
        if($type=="Activated"){$type= " where status = 1 ";}
        elseif($type=="deActivated"){$type= " where status = 0 ";}
        else {$type="";}
        $Query=" SELECT COUNT(`s_id`) as `serviceNum` FROM `service` ".$type." ";
        $Attributes=array();
        return $db->select($Query,$Attributes);
    }
    // count category
    public function countCategory ($db)
    {
        $Query=" SELECT COUNT(cat_id) as `categoryNum` FROM `category` ";
        $Attributes=array();
        return $db->select($Query,$Attributes);
    }
    //count order Num
    public function countOrder ($db)
    {
        $Query=" SELECT 			  
                  (SUM(CASE WHEN ordering.status = 1 THEN 1 ELSE 0 END)) as Completed
                , (SUM(CASE WHEN ordering.status = 0 THEN 1 ELSE 0 END)) as unCompleted
                , COUNT(*) as AllOrders
                FROM ordering";
        $Attributes=array();
        return $db->select($Query,$Attributes);
    }
    //count supervisors
    public function CountSupervisors ($db)
    {
        $Query=" SELECT COUNT(*) as `CountSupervisors` FROM `manager` WHERE manager.group_id=1 ";
        $Attributes=array();
        return $db->select($Query,$Attributes);
    }
    // clients statistics
    public function countMemberStatistics ($db , $from , $to)
    {
        $Query=" 
                SELECT MONTHNAME(STR_TO_DATE( EXTRACT(MONTH FROM dor) , '%m')) AS `Month_Name` , 
                count(*) AS `weight` from customer
                WHERE customer.dor BETWEEN ? AND ?
                GROUP BY `Month_Name`
                ";
        $Attributes=array($from , $to);
        return $db->select($Query,$Attributes);
    }
}
