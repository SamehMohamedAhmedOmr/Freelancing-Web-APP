
<?php
require_once '../oop/Person.php';

abstract class  Employee extends Person {

       /* Emplyoee Attributes */
   private $groupID;
   private $salary;
   private $hours;
   private $hireDate;
   private $photo_name;

    
    /*getter & setter methods*/
    function getGroupID() {
        return $this->groupID;
    }
    function setGroupID($groupID) {
        $this->groupID = $groupID;
    }
    function getSalary() {
        return $this->salary;
    }

    function getHours() {
      
        return $this->hours;
    }

    function getPhoto_name()
    {
    return $this->photo_name;
    }
    function getHireDate() {
        return $this->hireDate;
    }

    function setSalary($salary) 
    {
        if(empty($salary))
        {return"the salary you entered is empty";}
        elseif(!is_numeric($salary) || $salary<0)
        {return"the salary you entered is not vaild";}        
        else{ $this->salary = $salary; return NULL;}
    }

    function setHours($hours) 
    {
        if(empty($hours)){return"the hours you entered is empty";}
        elseif(!is_numeric($hours))
        {return"the hours you entered is not vaild";}
        elseif($hours<0 || $hours >24)
        {return "hours must be between 1-24";}
        else{$this->hours = $hours;return NULL;}
    }

    function setHireDate($hireDate) 
    {
        $validate_date = trim($hireDate);
        if(!isset($hireDate)  || $validate_date="" || empty($validate_date))
        {return "date can't be empty";}
        else { $this->hireDate = $hireDate;return NULL;}        
    }
    function setimg($photo_name,$photo_size,$mail , $check=0)
    {
        if($check==0)
        {
            $avilabelExtension = array ("jpeg","jpg","png","gif");
            $image_name_array = explode(".", $photo_name);
            $img_extension = end($image_name_array);
            $get_img_type= strtolower($img_extension);
            if(!in_array($get_img_type, $avilabelExtension))
            {return "the photo you entered is not vaild";}
            else if($photo_size>2097152 || $photo_size==0)
            { return "image can\'t be more than 2 MB"; }
            else
            {
                $imageDBName=$mail.'-'.$photo_name;
                $this->photo_name=$imageDBName;
                return NULL;            
            }
        }
        else
        {
           $this->photo_name=$photo_name;  
        }
    }

           /* class Methods */ 
   // override Methods
   
   public  static function Dashboard_login ($E_mail,$password,$option)
    {
        $db_connection = new Database();
        $query = "SELECT * FROM manager WHERE `E-mail` = ? and `password` = ? ";
        $Attributes = array($E_mail,$password);
        $result = $db_connection->select($query,$Attributes);
        if($result)
        {
            $data = $result[0];
            $type = $data['group_id'];
            if($type==0)
            {
                $user = new CEO();
            }
            else 
            {
                $user = new Supervisor();
            }
            $user->setID($data['mgr_id']);
            $user->setName($data['name']);
            $user->setE_mail($data['E-mail']);
            $user->setPassword($data['password']);
            $user->setimg($data['image'], 0, 0, 1);
            $user->setGroupID($data['group_id']);            
            $user->setHireDate($data['date']);
            $user->setHours($data['hours']);
            $user->setSalary($data['salary']);
            
            $_SESSION["Employee"] = serialize($user);
            if($option=='true')
            {
                // remember me button save E-mail & password in Cookie
                $hour = time()+3600*24*30; // 30 day cookie
                setcookie('E_mail', $E_mail, $hour);
                setcookie('pass', $password, $hour);
            }
            return "login_success";
        }
        else 
        {
          return "login_fail";
        }
    }

   public function RemoveService (Services $service){
      return $service->removeService($this->db_connection); 
   }

    public  function sendMessage (Message $m)
    {
        return $m->sendMessage($this->getID(), $this->db_connection);
        
    }
    public  function readMessage (Message $m ,$sender , $type)
    {
        $reciever = $this->getID();
        return  $m->readMessage($reciever,$sender,$this->db_connection , $type);
    } 
    
    public  function viewSelectedMsg ($sender , $reciever , $date)
    {
        $m = new CustomerService();
        return  $m->viewSpecificMessage($reciever, $sender, $date, $this->db_connection);
    }
    
    public  function viewSpecificMessage ($sender , $reciever , $date)
    {
         $m= new Manager_Msg();
        return  $m->viewSpecificMessage($reciever, $sender,$date, $this->db_connection);
    }
    
   
    
    public  function changeMessageStatus ($message_id)
    {
        $m = new Manager_Msg();
        $m->changeMessageStatus($message_id, $this->db_connection);
    }
    
    public function Mail_staff_E_mails(Supervisor $s , $mgr_mail)
    {
        $data =$s->Mail_staff_E_mail($mgr_mail , $this->getID());
        return $data;
    }
    public function MangeProfile ($connect)
    {
       $Query="UPDATE manager SET name=? , password=? , image=? WHERE `mgr_id`=".$this->getID()." ";
       $Attibutes=array
       (
            $this->getName(),
            $this->getPassword() , 
            $this->getPhoto_name()
       );
       return  $connect->update($Query,$Attibutes);       
    }
    // count members
    
    
    //count order Num
    public function countOrder ($system)
    {
        $data=  $system->countOrder($this->db_connection);
        return $data[0];
    }
    //count MailBox Message
    public function countMailBox ($message)
    {
        $data =  $message->countMessages($this->getID() , $this->db_connection);
        return $data[0];
    }
    
}
