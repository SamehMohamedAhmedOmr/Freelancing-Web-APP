<?php
require './includes/header.php';
//--------------------New Order Contact---------------------
if(isset($_SESSION["Client"]))
{
    $client = unserialize($_SESSION["Client"]);
    $clientID = $client->getID();
    if(isset($_GET['type']) && isset($_GET['order']) && isset($_GET['c']))
    {
      $type = $_GET['type'];
      $order_id = $_GET['order'];
      $client_2 = $_GET['c'];
    }
    else {
      header("Location: Home.php");
    }
}
//--------------------Upload---------------------
if (isset($_POST['uploadSubmit'])) {
  $fileName = $_FILES["fileToUpload"]["name"];
  $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];
  $fileSize = $_FILES["fileToUpload"]["size"];
  $fileError = $_FILES["fileToUpload"]["error"];

  // in case we want to check the extention
  $fileExt = explode('.',$fileName);
  $fileActualExt = strtolower(end($fileExt));
  $fileName_new="";
  // $allowed = array(... , .... , ...);
  // if(in_array(($fileActualExt,$allowed))){...}

  $msg = trim($_POST["message"]);
  $msg = stripslashes($msg);
  $msg = filter_var($msg,FILTER_SANITIZE_STRING);
  $msg = filter_var($msg,FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  if($fileError===0)
  {
    if($fileSize<1000000)
    {
      $fileName_new = uniqid('',true).'.'.$fileActualExt;
      $fileDestination = '..\uploads\\'.$fileName_new;
      $check = move_uploaded_file($fileTmpName,$fileDestination);
    }
  }
  if(strlen($msg)!=0 || $fileError===0)
  {
    $date = date('Y-m-d H:i:s');
    $oc = new order_contact();

    if($oc->sendcontact($order_id,$clientID,$client_2,$date,$msg,$fileName_new,$client->db_connection))
    {
      if($type==2)
      {
        if (isset($_POST['complete']))
        {
          $o = new Order();
          $o->compeleteOrder($order_id,$client->db_connection);
        }
      }
      Header("Location: viewOrderContact.php?type=".$type."&order=".$order_id."&c=".$client_2);
    }
  }
  else  {
    Header("Location: newOrderContact.php?type=".$type."&order=".$order_id."&c=".$client_2);
  }
}
//--------------------Download---------------------
if (isset($_GET['Download']))
{
    if(!empty($_GET["Download"]))
    {
      $fileName = basename($_GET["Download"]);
      $filePath = '..\uploads\\'.$fileName;
      if(file_exists($filePath) && !empty($fileName))
       {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        // read the file from disk
        readfile($filePath);
        exit;
        }
        else {
          echo "Error !";
        }
      }
}
?>
