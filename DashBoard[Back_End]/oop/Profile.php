<?php

class Profile {
       /* Profile Attributes */
   private $summary;
   private $country;
   private $zipCode;
   private $Activity = array ();
   private $Education = array ();
   private $Experience = array ();
   private $skills = array ();
   private $languages = array ();
   private $awards = array ();
   private $projects = array ();
   private $certification = array ();
       /* Profile Constructor */

   function __construct()
   {
        
   }

    /*getter & setter methods*/
    function setSummary($summary) {
        $this->summary = $summary;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    function setActivity($Activity) {
        $this->Activity[] = $Activity;
    }

    function setEducation($Education) {
        $this->Education[] = $Education;
    }

    function setExperience($Experience) {
        $this->Experience[] = $Experience;
    }

    function setSkills($skills) {
        $this->skills[] = $skills;
    }

    function setLanguages($languages) {
        $this->languages[] = $languages;
    }

    function setAwards($awards) {
        $this->awards[] = $awards;
    }

    function setProjects($projects) {
        $this->projects[] = $projects;
    }

    function setCertification($certification) {
        $this->certification[] = $certification;
    }

     /* class Methods */
    public function GetProfileData ($c_ID,$db_connection)
    {
        $Query="Select * From `profile` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }
    public function EditProfile ($c_ID,$data,$db_connection)
    {
        $Query="UPDATE profile SET summary=? , country=? , zip_code=? WHERE `c_id` = ?";
        $Attibutes=array
        (
            $data['summary'],
            $data['country'],
            $data['zip_code'],
            $c_ID
        );
        return $db_connection->update($Query,$Attibutes);
    }



        public function AddActivity ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `activity` (`c_id`,`activity`,`startd_date`,`end_date`) VALUES (?,?,?,?)";
         $attributes=array($c_ID,$data['activity'],$data['startd_date'],$data['end_date']);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteActivity ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `activity` WHERE c_id=$c_ID and activity='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateActivity ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `activity` SET `activity`=?,`startd_date`=?,`end_date`=? WHERE c_id=? and activity=?";
         $attributes=array($data['activity'],$data['startd_date'],$data['end_date'],$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetActivities ($c_ID,$db_connection)
    {
        $Query="Select * From `activity` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddAward ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `award` (`c_id`,`award`) VALUES (?,?)";
         $attributes=array($c_ID,$data);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteAward ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `award` WHERE c_id=$c_ID and award='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateAward ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `award` SET `award`=? WHERE `c_id`=? and `award`=?";
         $attributes=array($data,$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetAwards ($c_ID,$db_connection)
    {
        $Query="Select * From `award` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddCertification ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `certification` (`c_id`,`certification`) VALUES (?,?)";
         $attributes=array($c_ID,$data);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteCertification ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `certification` WHERE c_id=$c_ID and certification='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateCertification ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `certification` SET `certification`=? WHERE c_id=? and certification=?";
         $attributes=array($data,$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetCertifications ($c_ID,$db_connection)
    {
        $Query="Select * From `certification` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddExperience ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `experience` (`c_id`, `experience`, `start_date`, `end_date`) VALUES (?,?,?,?)";
         $attributes=array($c_ID,$data['experience'],$data['start_date'],$data['end_date']);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteExperience ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `experience` WHERE c_id=$c_ID and experience='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateExperience ($c_ID,$Pk,$data,$db_connection)
    {
      echo $data['experience']."  ".$data['start_date']."  ".$data['end_date']."  ".$c_ID."  ".$Pk;
         $sql="UPDATE `experience` SET `experience`=?,`start_date`=?,`end_date`=? WHERE c_id=? and experience=?";
         $attributes=array($data['experience'],$data['start_date'],$data['end_date'],$c_ID ,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetExperiences ($c_ID,$db_connection)
    {
        $Query="Select * From `experience` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddEducation ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `education`(`c_id`, `education`, `start_date`, `end_date`) VALUES (?,?,?,?)";
         $attributes=array($c_ID,$data['education'],$data['start_date'],$data['end_date']);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteEducation ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `education` WHERE c_id=$c_ID and education='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateEducation ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `education` SET `education`=?,`start_date`=?,`end_date`=? WHERE c_id=? and education=?";
         $attributes=array($data['education'],$data['start_date'],$data['end_date'],$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetEducations ($c_ID,$db_connection)
    {
        $Query="Select * From `education` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddLanguage ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `language` (`c_id`,`language`,`rate`) VALUES (?,?,?)";
         $attributes=array($c_ID,$data['language'],$data['rate']);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteLanguage ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `language` WHERE c_id=$c_ID and language='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateLanguage ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `language` SET `language`=?,`rate`=? WHERE c_id=? and language=?";
         $attributes=array($data['language'],$data['rate'],$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetLanguages ($c_ID,$db_connection)
    {
        $Query="Select * From `language` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddProject ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `project` (`c_id`,`project`) VALUES (?,?)";
         $attributes=array($c_ID,$data);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteProject ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `project` WHERE c_id=$c_ID and project='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateProject ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `project` SET `project`=? WHERE c_id=? and project=?";
         $attributes=array($data,$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
        public function GetProjects ($c_ID,$db_connection)
    {
        $Query="Select * From `project` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }



        public function AddSkill ($c_ID,$data,$db_connection)
    {
         $sql="INSERT INTO `skill` (`c_id`,`skill`,`rate`) VALUES (?,?,?)";
         $attributes=array($c_ID,$data['skill'],$data['rate']);
         return $db_connection->insert($sql,$attributes);
    }
        public function DeleteSkill ($c_ID,$Pk,$db_connection)
    {
         $sql="DELETE FROM `skill` WHERE c_id=$c_ID and skill='$Pk'";
         return $db_connection->delete($sql,null);
    }
        public function UpdateSkill ($c_ID,$Pk,$data,$db_connection)
    {
         $sql="UPDATE `skill` SET `skill`=?,`rate`=? WHERE c_id=? and skill=?";
         $attributes=array($data['skill'],$data['rate'],$c_ID,$Pk);
         return $db_connection->update($sql,$attributes);
    }
         public function GetSkills ($c_ID,$db_connection)
    {
        $Query="Select * From `skill` WHERE `c_id` = $c_ID";
        return $db_connection->select($Query,NULL);
    }


}
