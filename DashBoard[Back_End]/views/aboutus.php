<?php
include('includes/Header.php');


 $system = new System();
$telephones=$system->gettelphone($employee->db_connection);
$mail=$system->getmails($employee->db_connection);
 $adress=$system->getadresses($employee->db_connection);
 $fax=$system->getfaxes($employee->db_connection);
echo'
<div class="aboutus_container container-fluid">
    <div class="contactBox col-md-5 col-xs-12 text-center">

      <div class="contactTitle"><h3>Phones</h3></div>
      <div class="contactData">
        <div class="dataForm">
            <form class="form-horizontal" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST">';
      	     $CHECK=0;
      	     $count=0;

      if($telephones){
          foreach ($telephones as $values) {
      	       if($values['phone_numbers']!=''){
                  $count++;
                  $CHECK=1;
      	          echo
                  '<input type="text" name="number[]" class="dataBox form-control" readonly value="'.$count."- ".$values['phone_numbers'].'" >
                  <input class="aboutusChkBox" type="checkbox" name="delete[]" value="'.$values['id'].'">
                  <hr>
                  <br>';
                }}}
                if($employee->getGroupID()==0){
                echo '
                <input class="btn aboutusBtn" type="button" value="Add" onclick="addInput()" /><span id="responce"></span>';

                if($CHECK==1)
                  echo'<input class="btn aboutusBtnDel" type="submit" name="done" value="Delete"  ></button>';
                  echo'<input class="btn aboutusBtnSave hidden" type="submit" name="save" value="Save" id="save">';}
echo '
          </form>
        </div>
      </div>
    </div>


        <div class="contactBox col-md-5 col-md-offset-1 col-xs-12 text-center">

          <div class="contactTitle"><h3>Mails</h3></div>
          <div class="contactData">
            <div class="dataForm">
            <form class="form-horizontal" action="?" method="POST">';
          	     $CHECK=0;
          	     $count=0;

          if($mail){
              foreach ($mail as $values) {
                if($values['emails']!=''&&$values['emails']!==NULL){
                	  $CHECK=1;
                    $count++;

          	          echo
                      '<input type="text" name="number" class="dataBox form-control" readonly value="'.$count."- ".$values['emails'].'" >
                      <input class="aboutusChkBox" type="checkbox" name="delete[]" value="'.$values['id'].'">
                      <hr>
                      <br>';
                    }}}
  if($employee->getGroupID()==0){
            echo ' <input class="btn aboutusBtn" type="button" value="Add" onclick="addInput1()"/><span id="responce1"></span>';
            if($CHECK==1)
            echo'<input class="btn aboutusBtnDel" type="submit" name="emails" value="Delete"></button>';
            echo'<input class="btn aboutusBtnSave hidden" type="submit" name="mail" value="Save" id="save_mail" >';}

echo '
            </form></div>
          </div>
        </div>
</div>
<div class="aboutus_container container-fluid">
        <div class="contactBox col-md-5 col-xs-12 text-center">

          <div class="contactTitle"><h3>Addresses</h3></div>
          <div class="contactData">
            <div class="dataForm">
            <form class="form-horizontal" action="?" method="POST">';
          	     $CHECK=0;
          	     $count=0;

            if($adress){
              foreach ($adress as $values) {
                if($values['adresses']!=''){
               	  $CHECK=1;
                  $count++;

          	          echo
                      '<input  type="text" name="number" class="dataBox form-control" readonly value="'.$count."- ".$values['adresses'].'" >
                      <input class="aboutusChkBox"  type="checkbox" name="delete[]" value="'.$values['id'].'">
                      <hr>
                      <br>';
                    }}}
                      if($employee->getGroupID()==0){
            echo ' <input class="btn aboutusBtn" type="button" value="Add" onclick="addInput2()"/><span id="responce2"></span>';
            if($CHECK==1)
            echo'<input class="btn aboutusBtnDel" type="submit" name="adresses" value="Delete"></button>';
            echo'<input class="btn aboutusBtnSave hidden" type="submit" name="address" value="Save" id="save_address" >';}

            echo'
            </form></div>
          </div>
        </div>

        <div class="contactBox col-md-5 col-xs-12 text-center">

          <div class="contactTitle"><h3>Faxes</h3></div>
          <div class="contactData">
            <div class="dataForm">
            <form class="form-horizontal" action="?" method="POST">';
          	     $CHECK=0;
          	     $count=0;

                 if($fax){
                   foreach ($fax as $values) {
                     if($values['faxes']!=''){
                     	$CHECK=1;
                         $count++;
          	          echo
                      '<input  type="text" name="number" class="dataBox form-control" readonly value="'.$count."- ".$values['faxes'].'" >
                      <input class="aboutusChkBox"  type="checkbox" name="delete[]" value="'.$values['id'].'">
                      <hr>
                      <br>';
                    }}}

                    if($employee->getGroupID()==0){
            echo ' <input class="btn aboutusBtn" type="button"  value="Add" onclick="addInput3()"/><span id="responce3"></span>';
            if($CHECK==1)
            echo'<input class="btn aboutusBtnDel" type="submit" name="faxes" value="Delete"></button>';
            echo'<input class="btn aboutusBtnSave hidden" type="submit" name="fax" value="Save" id="save_fax" >';}
echo '
            </form></div>
          </div>
        </div>
      </div>
</div>';

if($_SERVER['REQUEST_METHOD']=='POST')
{
if(isset($_POST['done'])){
if(isset($_POST['delete'])){
 $id=$_POST['delete'];
        foreach($id as $key)
        {

 $system->deletephones($key,$employee->db_connection);
 header("Location: aboutus.php");
}}}
elseif(isset($_POST['emails'])){
if(isset($_POST['delete'])){
 $id=$_POST['delete'];
        foreach($id as $key)
        {

 $system->deletemails($key,$employee->db_connection);
 header("Location: aboutus.php");
}}}
elseif(isset($_POST['faxes'])){
if(isset($_POST['delete'])){
 $id=$_POST['delete'];
        foreach($id as $key)
        {

 $system->deletefaxes($key,$employee->db_connection);
 header("Location: aboutus.php");
}}}
elseif(isset($_POST['adresses'])){
if(isset($_POST['delete'])){
 $id=$_POST['delete'];
        foreach($id as $key)
        {

 $system->deleteadress($key,$employee->db_connection);
 header("Location: aboutus.php");
}}}
elseif(isset($_POST['save']))
{
    if(isset($_POST['emp'])){
$xamp=$_POST['emp'];
foreach ($xamp as $key) {
if($key!=''){
$system->insertphone($key,$employee->db_connection);
header("Location: aboutus.php");
}}
}}
elseif(isset($_POST['mail']))
{
    if(isset($_POST['emp'])){

$xamp=$_POST['emp'];
foreach ($xamp as $key) {
if($key!=''){
$system->insertmail($key,$employee->db_connection);
header("Location: aboutus.php");
}}
}}
elseif(isset($_POST['address']))
{if(isset($_POST['emp'])){
$xamp=$_POST['emp'];
foreach ($xamp as $key) {
if($key!=''){
$system->insertadress($key,$employee->db_connection);
header("Location: aboutus.php");
}}
}}
elseif(isset($_POST['fax']))
{if(isset($_POST['emp'])){
$xamp=$_POST['emp'];
foreach ($xamp as $key) {
if($key!=''){
$system->insertfax($key,$employee->db_connection);
header("Location: aboutus.php");
}}
}}
}

 ?>

<script>
var countBox =1;
var boxName = 0;
function addInput()
{
     var boxName="phonenumber"+countBox;
document.getElementById('responce').innerHTML+='<br/><input class="form-control addInput" type="text" id="'+boxName+'" placeholder="'+boxName+'" " name="emp[]"  /><br/>';
     countBox += 1;
      var id = document.getElementById('save');
      $(id).removeClass("hidden");
  }
function addInput1()
{
     var boxName="E_mail"+countBox;
document.getElementById('responce1').innerHTML+='<br/><input class="form-control addInput" type="text" id="'+boxName+'" placeholder="'+boxName+'" " name="emp[]"  /><br/>';
     countBox += 1;
   var id = document.getElementById('save_mail');
      $(id).removeClass("hidden");
}
function addInput2()
{
     var boxName="adress"+countBox;
document.getElementById('responce2').innerHTML+='<br/><input class="form-control addInput" type="text" id="'+boxName+'" placeholder="'+boxName+'" " name="emp[]"  /><br/>';
     countBox += 1;
   var id = document.getElementById('save_address');
      $(id).removeClass("hidden");
}
function addInput3()
{
     var boxName="fax"+countBox;
document.getElementById('responce3').innerHTML+='<br/><input class="form-control addInput" type="text" id="'+boxName+'" placeholder="'+boxName+'" " name="emp[]"  /><br/>';
     countBox += 1;
   var id = document.getElementById('save_fax');
      $(id).removeClass("hidden");
}
</script>
<?php
 include('includes/Footer.php');
