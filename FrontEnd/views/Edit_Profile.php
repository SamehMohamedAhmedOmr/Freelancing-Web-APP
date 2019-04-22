<?php

// AJAX FILE

require '../config/includes.php';

if($_POST["action"]=="updateClientInfo" && isset($_POST["name"]) && isset($_POST["client_ID"]))
 {
    $c1 = new Client();
    $c1->setID($_POST["client_ID"]);

    $name = trim($_POST["name"]);
    $name = stripslashes($name);
    $name = filter_var($name,FILTER_SANITIZE_STRING);
    $name = filter_var($name,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $c1->setName($name);

      $data = array();

      $data['summary']=filter_var($_POST["summary"],FILTER_SANITIZE_STRING);
      $data['country']=$_POST["country"];
      $data['zip_code']=filter_var($_POST["zip"],FILTER_SANITIZE_STRING);

      if($c1->EditProfile($data) && $c1->editInfo())
      {
           echo "Changes Saved";
      }
      else
      {
          echo "Save failed";
      }
 }

if($_POST["action"]=="updateClientPass" && isset($_POST["currentPass"]) && isset($_POST["newPass"]) && isset($_POST["rePass"]) && isset($_POST["client_ID"]))
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->setPassword($_POST["newPass"]);
       if($check)
       {
           echo $check;
       }
       else
       {
           $check = $c1->setNewPassword();
           echo $check;
       }
 }
  // -----------------------------------------------DELETE----------------
if($_POST["action"]=="deleteaward" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->DeleteAward($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

       echo $check;
 }
if($_POST["action"]=="deletecertification" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->DeleteCertification($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

       echo $check;
 }
if($_POST["action"]=="deleteproject" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->DeleteProject($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

       echo $check;
 }
if($_POST["action"]=="deleteexperience" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
{
      $c1 = new Client();
      $c1->setID($_POST["client_ID"]);

      $check = $c1->getProfile()->DeleteExperience($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

      echo $check;
}
if($_POST["action"]=="deleteeducation" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
{
     $c1 = new Client();
     $c1->setID($_POST["client_ID"]);

     $check = $c1->getProfile()->DeleteEducation($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

     echo $check;
}
if($_POST["action"]=="deleteactivity" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
{
     $c1 = new Client();
     $c1->setID($_POST["client_ID"]);

     $check = $c1->getProfile()->DeleteActivity($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

     echo $check;
}
if($_POST["action"]=="deleteskill" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
{
     $c1 = new Client();
     $c1->setID($_POST["client_ID"]);

     $check = $c1->getProfile()->DeleteSkill($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

     echo $check;
}
if($_POST["action"]=="deletelanguage" && isset($_POST["pk"]) && isset($_POST["client_ID"]) )
{
     $c1 = new Client();
     $c1->setID($_POST["client_ID"]);

     $check = $c1->getProfile()->DeleteLanguage($_POST["client_ID"],$_POST["pk"],$c1->db_connection);

     echo $check;
}
 // -----------------------------------------------UPDATE----------------
if($_POST["action"]=="updateaward" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["client_ID"]) )
{
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->UpdateAward($_POST["client_ID"],$_POST["pk_id"],$_POST["description"],$c1->db_connection);
       echo $check;
 }
if($_POST["action"]=="updateproject" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["client_ID"]) )
{
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->UpdateProject($_POST["client_ID"],$_POST["pk_id"],$_POST["description"],$c1->db_connection);

         echo $check;
 }
if($_POST["action"]=="updatecertification" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["client_ID"]) )
{
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->UpdateCertification($_POST["client_ID"],$_POST["pk_id"],$_POST["description"],$c1->db_connection);
         echo $check;
 }
if($_POST["action"]=="updatelanguage" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["rate"]) && isset($_POST["client_ID"]) )
{
     $c1 = new Client();
     $c1->setID($_POST["client_ID"]);

     $data = array();
     $data['language'] = $_POST["description"];
     $data['rate'] = $_POST["rate"];

     $check = $c1->getProfile()->UpdateLanguage($_POST["client_ID"],$_POST["pk_id"],$data,$c1->db_connection);
       echo $check;
}
if($_POST["action"]=="updateexperience" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
{
    $c1 = new Client();
    $c1->setID($_POST["client_ID"]);

    $data = array();
    $data['experience'] = $_POST["description"];
    $data['start_date'] = $_POST["startDate"];
    $data['end_date'] = $_POST["endDate"];

    $check = $c1->getProfile()->UpdateExperience($_POST["client_ID"],$_POST["pk_id"],$data,$c1->db_connection);
      echo $check;
}
if($_POST["action"]=="updateeducation" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
{
    $c1 = new Client();
    $c1->setID($_POST["client_ID"]);

    $data = array();
    $data['education'] = $_POST["description"];
    $data['start_date'] = $_POST["startDate"];
    $data['end_date'] = $_POST["endDate"];

    $check = $c1->getProfile()->UpdateEducation($_POST["client_ID"],$_POST["pk_id"],$data,$c1->db_connection);
      echo $check;
}
if($_POST["action"]=="updateactivity" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
{
    $c1 = new Client();
    $c1->setID($_POST["client_ID"]);

    $data = array();
    $data['activity'] = $_POST["description"];
    $data['startd_date'] = $_POST["startDate"];
    $data['end_date'] = $_POST["endDate"];

    $check = $c1->getProfile()->UpdateActivity($_POST["client_ID"],$_POST["pk_id"],$data,$c1->db_connection);
      echo $check;
}
if($_POST["action"]=="updateskill" && isset($_POST["pk_id"]) && isset($_POST["description"]) && isset($_POST["rate"]) && isset($_POST["client_ID"]) )
{
    $c1 = new Client();
    $c1->setID($_POST["client_ID"]);

    $data = array();
    $data['skill'] = $_POST["description"];
    $data['rate'] = $_POST["rate"];

    $check = $c1->getProfile()->UpdateSkill($_POST["client_ID"],$_POST["pk_id"],$data,$c1->db_connection);
      echo $check;
}
// -----------------------------------------------ADD----------------
if($_POST["action"]=="addaward" && isset($_POST["pk_id"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->AddAward($_POST["client_ID"],$_POST["pk_id"],$c1->db_connection);
       //if($check)
         echo $check;
 }
if($_POST["action"]=="addproject" && isset($_POST["pk_id"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->AddProject($_POST["client_ID"],$_POST["pk_id"],$c1->db_connection);
       //if($check)
         echo $check;
 }
if($_POST["action"]=="addcertification" && isset($_POST["pk_id"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $check = $c1->getProfile()->AddCertification($_POST["client_ID"],$_POST["pk_id"],$c1->db_connection);
       //if($check)
       echo $check;
 }
if($_POST["action"]=="addexperience" && isset($_POST["pk_id"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
 {
       $c1 = new Client();
       $c1->setID($_POST["client_ID"]);

       $data = array();
       $data['experience'] = $_POST["pk_id"];
       $data['start_date'] = $_POST["startDate"];
       $data['end_date'] = $_POST["endDate"];

       $check = $c1->getProfile()->AddExperience($c1->getID(),$data,$c1->db_connection);

       //if($check)
         echo $check;
 }
if($_POST["action"]=="addeducation" && isset($_POST["pk_id"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
{
   $c1 = new Client();
   $c1->setID($_POST["client_ID"]);

   $data = array();
   $data['education'] = $_POST["pk_id"];
   $data['start_date'] = $_POST["startDate"];
   $data['end_date'] = $_POST["endDate"];

   $check = $c1->getProfile()->AddEducation($c1->getID(),$data,$c1->db_connection);

   //if($check)
     echo $check;
}
if($_POST["action"]=="addactivity" && isset($_POST["pk_id"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["client_ID"]) )
{
  $c1 = new Client();
  $c1->setID($_POST["client_ID"]);

  $data = array();
  $data['activity'] = $_POST["pk_id"];
  $data['startd_date'] = $_POST["startDate"];
  $data['end_date'] = $_POST["endDate"];

  $check = $c1->getProfile()->AddActivity($c1->getID(),$data,$c1->db_connection);

  //if($check)
    echo $check;
}
if($_POST["action"]=="addlanguage" && isset($_POST["pk_id"]) && isset($_POST["rate"]) && isset($_POST["client_ID"]) )
{
  $c1 = new Client();
  $c1->setID($_POST["client_ID"]);

  $data = array();
  $data['language'] = $_POST["pk_id"];
  $data['rate'] = $_POST["rate"];

  $check = $c1->getProfile()->AddLanguage($c1->getID(),$data,$c1->db_connection);

  //if($check)
    echo $check;
}
if($_POST["action"]=="addskill" && isset($_POST["pk_id"]) && isset($_POST["rate"]) && isset($_POST["client_ID"]) )
{
  $c1 = new Client();
  $c1->setID($_POST["client_ID"]);

  $data = array();
  $data['skill'] = $_POST["pk_id"];
  $data['rate'] = $_POST["rate"];

  $check = $c1->getProfile()->AddSkill($c1->getID(),$data,$c1->db_connection);

  //if($check)
    echo $check;
}
