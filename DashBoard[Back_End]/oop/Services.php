<?php

class Services {
    // class attributes
    private $service_id;
    private $service_name;
    private $service_view;
    private $status;
    private $service_date;
    private $tags;
    private $price;
    private $deliver_date;
    private $description;
    private $images = array();
    private $type; // Category
    private $completedOrders;
   private $BindingOrders;
    // Constructor
    function __construct() {
        $this->type =  new Category();
    }
    //getter & setter
    function getService_id() {
        return $this->service_id;
    }

    function getService_name() {
        return $this->service_name;
    }

    function getService_view() {
        return $this->service_view;
    }

    function getStatus() {
        return $this->status;
    }

    function getService_date() {
        return $this->service_date;
    }

    function getTags() {
        return $this->tags;
    }
    function getCompletedOrders() {
        return $this->completedOrders;
    }

    function getBindingOrders() {
        return $this->BindingOrders;
    }

    function setCompletedOrders($completedOrders) {
        $this->completedOrders = $completedOrders;
    }

    function setBindingOrders($BindingOrders) {
        $this->BindingOrders = $BindingOrders;
    }

        function getPrice() {
        return $this->price;
    }

    function getDeliver_date() {
        return $this->deliver_date;
    }

    function getDescription() {
        return $this->description;
    }

    function getImages() {
        return $this->images;
    }

    function getType() {
        return $this->type->getCat_id();
    }
    function getServiceStars ($db , $service_id)
    {
        $sql = "
                SELECT
                cast(sum(e.stars)/count(e.s_id) as integer) as `stars`
                FROM estimate e, service s
                where e.s_id=s.s_id and  s.status=1 and s.s_id= ?
                group by e.s_id ";
        $attributes = array($service_id);
        return $db->select($sql,$attributes);
    }

    function setService_id($service_id,$dbConn) {
         $service_id = trim($service_id);
         $service_id = strip_tags($service_id);
         $service_id = filter_var($service_id,FILTER_SANITIZE_NUMBER_INT);

            $sql = 'SELECT * FROM service WHERE `s_id`=?';
            $attributes = array ($service_id);
            if($dbConn->select($sql,$attributes)==0){return " this service is not Exist anymore";}
            else {$this->service_id = $service_id;}
    }

    function setService_name($service_name) {
        $this->service_name = $service_name;
    }

    function setService_view($service_view) {
        $this->service_view = $service_view;
    }

    function setStatus($status) {
         $status = trim($status);
         $status = strip_tags($status);
         $status = filter_var($status,FILTER_SANITIZE_NUMBER_INT);
         if($status==0 || $status ==1)
         {$this->status = $status;}
         else {return"invalid service status";}
    }

    function setService_date($service_date) {
        $this->service_date = $service_date;
    }

    function setTags($tags) {
        $this->tags = $tags;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setDeliver_date($deliver_date) {
        $this->deliver_date = $deliver_date;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setImages($db_connection) {
        $this->images = $this->getServiceImages($db_connection);
    }

    function setType($type,$db) {
        $this->type->setCat_id($type,$db);
    }

    // class method
     public function viewOwnerServices ($db_connection,$mgr_id , $limit , $type)
    {
        // type option
        if($type=="Activated"){$type=" AND  status = 1 ";}
        elseif($type=="deActivated"){$type=" AND  status = 0  ";}
        else{$type=" ";}
        // limit option
        if($limit!=0)
        {$limit= 'LIMIT '.$limit.' ';}
        else {$limit=' ';}
      // call method select in class Database and make Query in it
        $sql = 'SELECT s.*,`cat`.`name` as Category,`cust`.`c_name` as Owner
                FROM service s, category cat , customer cust
                WHERE `s`.`mgr_id`= ? and `cat`.`cat_id`=`s`.`cat_id` and `cust`.`c_id`=`s`.`c_id` '.$type.'
                 order by `s_id` desc '.$limit.'';
        $attributes = array(
            $mgr_id
        );
        $check = $db_connection->select($sql,$attributes);
        return $check;
    }


    public function viewAllServices ($db , $cat_id , $where , $from , $to)
    {
         $sql =
            "select service.* , service_image.image as image
            FROM  service , service_image
            where   service.status=1 AND service.s_id = service_image.s_id AND service.cat_id= ? $where
            group by service.s_id order by service.s_date DESC LIMIT $from,$to";

         $attributes= array($cat_id);
         return $db->select($sql,$attributes);
    }

    public function viewCustomerServices ($db , $c_id , $where , $from , $to)
    {
         $sql =
            "select service.* , service_image.image as image
            FROM  service , service_image
            where service.s_id = service_image.s_id AND service.c_id= ? $where
            group by service.s_id order by service.s_date DESC LIMIT $from,$to";

         $attributes= array($c_id);
         return $db->select($sql,$attributes);
    }

    public function viewServicesByEstimate ($db , $cat_id , $where , $order , $way , $from , $to)
    {
         $sql =
            "SELECT service.*,s.image , COUNT(ordering.s_id) as orders,estimate.stars
            from service
            LEFT JOIN (SELECT service_image.* FROM service_image GROUP BY service_image.s_id) s ON service.s_id = s.s_id
            LEFT JOIN ordering ON service.s_id = ordering.s_id
            LEFT JOIN estimate ON service.s_id = estimate.s_id
            WHERE service.status=1 AND service.cat_id=? $where
            GROUP BY service.s_id order by $order $way LIMIT $from,$to";

         $attributes= array($cat_id);
         return $db->select($sql,$attributes);
    }

    public function showSelectedServices ($db_connection){
        $sql =
            '
                SELECT service.*
                , (SUM(CASE WHEN ordering.status = 0 THEN 1 ELSE 0 END)) as uncompleted_order
                , (SUM(CASE WHEN ordering.status = 1 THEN 1 ELSE 0 END)) as completed_order
                from service
                LEFT JOIN ordering ON ordering.s_id = service.`s_id`
                where service.`s_id`=?
                GROUP BY service.s_id
            ';
        $attributes = array(
            $this->getService_id()
        );
        $check = $db_connection->select($sql,$attributes);
        return $check;
    }

   public function getServiceImages($db_connection){
        $sql = 'SELECT `image` from service_image where `s_id`=?';
        $attributes = array(
            $this->getService_id()
        );
        $check = $db_connection->select($sql,$attributes);
        return $check;
    }

    public function searchForServices($search_key_word,$db_connection,$mgr_id)
    {
        $sql = "SELECT s.*,`cat`.`name` as Category,`cust`.`c_name` as Owner
                FROM service s, category cat , customer cust
                WHERE `cat`.`cat_id`=`s`.`cat_id` and `cust`.`c_id`=`s`.`c_id`
                    and s.`name` like '%$search_key_word%' and  s.`mgr_id` = ?";

        $attributes = array(
            $mgr_id
        );
        return $db_connection->select($sql,$attributes);
    }


    public  function removeService ($db_connection){
        $sql1 = 'DELETE FROM service_image WHERE `s_id` = ?';

        $sql = 'DELETE FROM service WHERE `s_id` = ?';

        $attributes  = array(
            $this->getService_id()
        );

        $check = $db_connection->delete($sql1,$attributes);
        if ($check) {
            return $checking = $db_connection->delete($sql,$attributes);
        }
        return false;
    }

     public function addService ($db_connection,$serviceName,$Description,$category,$tags,$Duration,$price,$FilesName, $FilesTmp_Name,$c_id)
    {
        $sql1 = "SELECT `mgr_id` from category where `cat_id`= ?";
        $Attributes1 = array($category);
        $result1 = $db_connection->select($sql1,$Attributes1);

        if($result1)
        {
            $sql2 = "INSERT INTO service (`name`,`view`,`description`,`status`,`s_date`,`tags`,`price`,`d_date`,`c_id`,`cat_id`,`mgr_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $Attributes2 = array($serviceName,0,$Description,0,date("Y-m-d"),$tags,$price,$Duration,$c_id,$category,$result1[0]['mgr_id']);
            $result2 = $db_connection->insert($sql2,$Attributes2);
            if($result2)
            {
                $sql3 = "SELECT `s_id` from service where `name`= ? And `view`= ? And `description`= ? And `status`= ? And `s_date`= ? And `tags`= ? And `price`= ? And `d_date`= ? And `c_id`= ? And `cat_id`= ? And `mgr_id`= ?";
                $result3 = $db_connection->select($sql3,$Attributes2);
                if($result3)
                {
                    $len = count($FilesName);
                    for ($i=0; $i<$len; $i++)
                    {
                        $path =  "..\Images\\service_images\\".$result3[0]['s_id'].'_'.$FilesName[$i];
                        $check_img = move_uploaded_file($FilesTmp_Name[$i],$path);
                        if($check_img)
                        {
                            $sql4= "INSERT INTO service_image values(?,?)";
                            $Attributes = array($result3[0]['s_id'],$result3[0]['s_id'].'_'.$FilesName[$i]);
                            $result4 = $db_connection->insert($sql4,$Attributes);
                        }

                    }
                    return $result4;
                }
            }
            return $result2;
        }
        return $result1;
    }

  public function viewTopService($db , $option)
    {
        $sql = "SELECT s.* , `service_image`.`image` as `image` ,
                cast(sum(e.stars)/count(e.s_id) as integer) as `stars`
                FROM estimate e, service s , `service_image`
                where e.s_id=s.s_id and  s.status=1 AND s.s_id = `service_image`.`s_id` $option
                group by e.s_id order by e.stars DESC LIMIT 10 " ;

         return  $db->select($sql,NULL);
    }
    public function UpdateServiceInfo($db , $serviceName, $Description, $tags, $Duration, $price, $s_id)
    {
        $sql = "UPDATE `service` SET `name` = ?, `description` = ?,
                 `tags` = ?, `price` = ?, `d_date` = ?
                 WHERE `service`.`s_id` = ?" ;

        $Attribute = array($serviceName,$Description,$tags,$price,$Duration,$s_id);

         return  $db->update($sql,$Attribute);
    }
// 2 functions accept service & reject service combined into one function update service status
    public function updateServiceStatus($db,$supervisor_id,CustomerService $customerservice){
        $sql = 'UPDATE `service` SET `status`= ?  WHERE `s_id`= ?';
        $attributes = array(
            $this->getStatus(),
            $this->getService_id()
        );
        $check = $db->update($sql,$attributes);

        if($check = 1){
            $query = 'SELECT s.*, `c`.`c_name` , `c`.`E-mail` from service s,customer c WHERE `s_id`= ?
                      And `c`.`c_id` = `s`.`c_id` ';
            $attribute = array(
            $this->getService_id()
            );
            $return = $db->select($query,$attribute);
            if($return){
                $data = $return[0];

                $customerservice->setStatus(0);
                $customerservice->setService_id($this->getService_id());
                $returnValue = $customerservice->setTo($data['E-mail'],$db);

                if($this->getStatus()==1){

                    $customerservice->setSubject('Your Service '.$data['name'].' is available to other web-site member');
                    $customerservice->setMessage('Congratulation '.$data['c_name'].' , your service '.$data['name']. ' is accepted');

                    if($returnValue == true){
                        return $customerservice->acceptServiceRequest($supervisor_id,$db);
                    }
                    else {
                        return false;
                    }
                }
                elseif($this->getStatus()==0){
                    $customerservice->setSubject('Your Service  '.$data['name'].' is canceled');
                    $customerservice->setMessage('unfortunately '.$data['c_name'].' , your service '.$data['name']. ' is cannot be viewed by users');

                    if($returnValue == true){
                        return $customerservice->rejectServiceRequest($supervisor_id,$db);
                    }
                    else {
                        return false;
                    }
                }
            }
        }
        else{
            return false;
        }

    }

     public function getOwner($db,Client $client){
        $client_id=  $client->getID();
        $sql =
                'SELECT `c_name` , `image` , profile.summary as summary FROM customer
                LEFT JOIN profile on profile.c_id=customer.c_id
                WHERE customer.`c_id`=?';
        $attributes = array ($client_id);
        return $data = $db->select($sql,$attributes);
    }

    public function showServiceEstimates(Estimate $e, $db)
    {
        // $sql=" SELECT estimate.* , customer.c_name , customer.image
        //        FROM estimate , customer , service WHERE estimate.c_id=customer.c_id AND service.s_id=estimate.s_id AND service.s_id=?";
        //
        // $attributes = array ($this->getService_id());
        // return $data = $db->select($sql,$attributes);
        return $e->showServiceEstimates($this->getService_id(),$db);
    }

    public function setOwnerID($id,Client $client){
        $client->setID($id);
    }

    public function serviceStatistics ($db , $from , $to)
    {
        $sql =
                " SELECT category.name as catName , COUNT(service.s_id) as serviceNum from category
                LEFT join service on  category.cat_id = service.cat_id AND service.s_date BETWEEN '$from' AND '$to'
                GROUP BY category.name ";
        return $data = $db->select($sql,NULL);
    }

    public function serviceCounter_view ($db)
    {
        $sql = "UPDATE service SET service.view = service.view+1 WHERE service.s_id = ? ";
        $attributes = array ($this->getService_id());
        return $db->update($sql , $attributes);
    }
}
