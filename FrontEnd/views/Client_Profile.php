<?php
 require './includes/header.php';
$id='';
if(isset($_GET['id']))
{
    $id=$_GET['id'];
}
else
{
    header("Location: Home.php");
    exit();
}
?>
<?php
// VIEW FILE
if($_SERVER['REQUEST_METHOD']=='POST')
{
   //validate image
   $img_flag=0;
   $oldimage;

   if(isset($_FILES['image']) &&!empty($_FILES['image']['name'])&&
    $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE)
   {
        $oldimage =  $client->getImage();
       $Error = array();
        
       $img_name= $_FILES['image']['name'];
       $image_tmp=$_FILES['image']['tmp_name'];
       $img_type= $_FILES['image']['type'];
       $img_size=$_FILES['image']['size'];
       //validate image
       $check=$client->setimg($img_name,$img_size,$client->getE_mail());
       if($check!=NULL)
       {$Errors[]=$check;}
       else {$img_flag=1;}
   }

   if(empty($Errors))
   {
       $check_img = true;
       if($img_flag==1)
       {
           $path =  "..\Images\\client_images\\".$client->getImage();
           $check_img = move_uploaded_file($image_tmp,$path);
           if($check_img==false)
            {
              $c1->EditProfile($data);
            }
       }
       if($check_img==true)
       {
           $check = $client->uploadImage();
           if($check==1)
           {
               if(isset($_SESSION['fbImage']))
               {unset($_SESSION['fbImage']);}
               if($oldimage!=$client->getImage())
               {
//                   $Path= '/Images/client_images/'.$oldimage;
//                   chown($Path , 666);
//                   unlink($path);   
//                   echo 'path = '.$path;
               }
           }

       }
   }
   $url = "client_profile.php?id=".$client->getID();
   header("Location: $url");
   exit();
}
?>
        <?php
            $c1= new Client();
            $c1->setID($id);
            $check = $c1->viewInfo();
            if($check==0)
            {
                header("Location: Home.php");
                exit();
            }
            $data = $c1->viewProfile();            
        ?>
        <button onclick="topFunction()" id="pr_topBtn" title="Go to top"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
        <div class="container">
            <div class="row">
              <div class="pr_page col-xs-12 col-xs-offset-0 text-center">
                <div class="pr_leftSec col-xs-12 col-md-3">
                  <div id="basicInfo">
                    <div class="pr_leftHeader">
                        <h3 id="c_name"><?php echo $c1->getName(); ?></h3>
                        <hr>
                        <?php
                        if(strpos($c1->getImage(),'https:') !== false)
                        {
                            echo '<img id="autoload" src="'.$c1->getImage().'" >';
                        }
                        else if ($c1->getImage() == '')
                        {
                            echo '<img id="autoload" src="..\Images\client_images\default.png" >';
                        }
                        else
                        {echo '<img id="autoload" src="..\Images\\client_images\\'.$c1->getImage().'" >';}
                        ?>
                        
                    </div>
                    <div class="pr_leftBody">
                        <p id="c_summary"><?php echo $data[0]['summary'] ?></p>
                        <h5> <i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $data[0]['country']; ?></h5>
                        <hr>
                    </div>

                    <div class="pr_leftInfo" id="basicInfo2">
                            <div title="Gender" class="pr_leftBtn"><i class="fa fa-venus-mars"></i></div>
                            <span><?php if($c1->getGender()) echo 'Male'; else echo 'Female'; ?></span>
                            <br>
                            <div title="Date of birth"class="pr_leftBtn"><i class="fa fa-calendar"></i></div>
                            <span><?php echo ($c1->getDOB()); ?></span>
                            <br>
                            <div title="Mail" class="pr_leftBtn"><i class="fa fa-envelope"></i></div>
                            <span><?php echo $c1->getE_mail(); ?></span>
                    </div>

                    <?php
                    if($client->getID()==$id){
                        echo'<button  id="editInfoBtn" class="btn pr_editBtn col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3" onclick="updInfoModal('.$data[0]['country'].')" type="button" name="editInfo" data-toggle="modal" data-target="#edit_modal">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</button>';
                    }
                    ?>

                  </div>
                </div>
                <div class="pr_rightSec col-xs-12 col-md-7 col-md-offset-1">
                    <!-- Awards -->
                    <div class="pr_dataSquare">
                        <button class="pr_dataBtn pr_dataBtnTop btn" data-toggle="collapse" data-target="#awardsList"><i class="fa fa-bars" aria-hidden="true"></i><span>Awards</span></button>
                        <div class="pr_dataContent collapse in" id="awardsList">
                                <div id="awards">
                                <?php
                                if($client->getID()==$id)
                                {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'award\')" name="addAward" data-toggle="modal" data-target="#add_modal"></i>';}
                                    $awards = $c1->getProfile()->GetAwards($c1->getID(),$c1->db_connection);
                                    if($awards!=0)
                                    {
                                        $count=0;
                                        foreach ($awards as $index)
                                        {
                                           echo'
                                           <dt>Description : </dt>
                                               <div class="pr_desc">
                                               <dd>'.$index['award'].'</dd>
                                            </div>
                                            <div class="clearfix"></div><br>
                                            ';
                                            
                                           if($client->getID()==$id)
                                           {
                                             echo '
                                                <div class="pr_Btns">
                                                  <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['award'].'\',\'award\')" value="'.$index['award'].'" type="button" name="editAward" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                  <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['award'].'\',\'award\')" value="'.$index['award'].'" type="button" name="delAward"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                </div><br>
                                           '; 
                                           }
                                          
                                        }
                                    }
                                    else{
                                        echo '<h5> You have not any awards </h5>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <!-- Projects -->
                    <div class="pr_dataSquare">
                        <buton class="pr_dataBtn pr_dataBtnTop btn" data-toggle="collapse" data-target="#projectsList"><i class="fa fa-bars" aria-hidden="true"></i><span>Projects</span></buton>
                        <div class="pr_dataContent collapse" id="projectsList">
                                <div id="projects">
                                    <?php
                                    if($client->getID()==$id)
                                    {
                                        echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'project\')" name="addProject" data-toggle="modal" data-target="#add_modal"></i>';
                                    }
                                    
                                    $projects = $c1->getProfile()->GetProjects($c1->getID(),$c1->db_connection);
                                    if($projects!=0)
                                    {
                                         $count=0;
                                         foreach ($projects as $index)
                                         {
                                            echo'
                                            <dt>Description : </dt>
                                              <div class="pr_desc">
                                                <dd>'.$index['project'].'</dd>
                                              </div>
                                             <div class="clearfix"></div><br>
                                              ';
                                            if($client->getID()==$id)
                                            {
                                                echo ' 
                                                    <div class="pr_Btns">
                                                      <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['project'].'\',\'project\')" value="'.$index['project'].'" type="button" data-toggle="modal" data-target="#update_modal" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                      <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['project'].'\',\'project\')" value="'.$index['project'].'"  type="button"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                    </div> <br>
                                                      <hr> 
                                                  ';
                                            }
                                         }
                                    }
                                    else{
                                        echo '<h5> You have not any projects </h5>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <!-- Certifications -->
                    <div class="pr_dataSquare">
                        <buton buton class="pr_dataBtn pr_dataBtnMid btn" data-toggle="collapse" data-target="#certificationsList"><i class="fa fa-bars" aria-hidden="true"></i><span>Certifications</span></buton>
                        <div class="pr_dataContent collapse" id="certificationsList">
                                <div id="certifications">
                                    <?php
                                    if($client->getID()==$id)
                                    {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'certification\')" name="addCertification" data-toggle="modal" data-target="#add_modal"></i>';}
                                    
                                    $certifications = $c1->getProfile()->GetCertifications($c1->getID(),$c1->db_connection);
                                    if($certifications!=0)
                                    {
                                         $count=0;
                                         foreach ($certifications as $index)
                                         {
                                            echo'
                                            <dt>Description : </dt>
                                              <div class="pr_desc">
                                                <dd>'.$index['certification'].'</dd>
                                              </div>
                                              <div class="clearfix"></div><br>

                                              ';
                                            if($client->getID()==$id)
                                            {
                                                echo 
                                                '<div class="pr_Btns">
                                                    <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['certification'].'\',\'certification\')" value="'.$index['certification'].'" type="button" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                    <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['certification'].'\',\'certification\')" value="'.$index['certification'].'" type="button" name="delCertification" style="background:darkred; border-color:darkred"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                </div> <br>
                                                <hr>';
                                            }                                              
                                         }
                                    }
                                    else{
                                        echo '<h5> You have not any certifications </h5>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <!-- Activities -->
                        <div class="pr_dataSquare">
                          <buton buton class="pr_dataBtn pr_dataBtnCenter btn" data-toggle="collapse" data-target="#activityList"><i class="fa fa-bars" aria-hidden="true"></i><span>Activities</span></buton>
                          <div class="pr_dataContent collapse" id="activityList">
                                      <div id="activities">
                                        <?php
                                        if($client->getID()==$id)
                                        {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'activity\')" name="addActivity" data-toggle="modal" data-target="#add_modal"></i>';}
                                          $activities = $c1->getProfile()->GetActivities($c1->getID(),$c1->db_connection);
                                          if($activities!=0)
                                          {
                                               $count=0;
                                               foreach ($activities as $index)
                                               {
                                                  echo'
                                                  <dt>Description : </dt>
                                                    <div class="pr_desc">
                                                      <dd>'.$index['activity'].'
                                                      <br>
                                                      <div class="pd_dates">
                                                        <h4>- Start date :<h4>
                                                        <span>'.$index['startd_date'].'</span>
                                                        <h4>- End date :<h4>
                                                        <span>'.$index['end_date'].'</span>
                                                      </div></dd>
                                                    </div>
                                                    <div class="clearfix"></div><br>
                                                    ';
                                                  if($client->getID()==$id)
                                                    {
                                                        echo '                                                 
                                                        <div class="pr_Btns">
                                                          <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['activity'].'\',\'activity\')" value="'.$index['activity'].'" type="button" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                          <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['activity'].'\',\'activity\')" value="'.$index['activity'].'" type="button" name="delActivity"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                        </div> <br>
                                                        <hr>
                                                      ';
                                                    }
                                               }
                                          }
                                          else{
                                              echo '<h5> You have not any activities </h5>';

                                          }
                                          ?>
                                      </div>
                                  </div>
                              </div>

                    <!-- Educations -->
                        <div class="pr_dataSquare">
                            <buton class="pr_dataBtn pr_dataBtnCenter btn" data-toggle="collapse" data-target="#educationList"><i class="fa fa-bars" aria-hidden="true"></i><span>Educations</span></buton>
                            <div class="pr_dataContent collapse" id="educationList">
                                          <div id="educations">
                                              <?php
                                                if($client->getID()==$id)
                                                {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'education\')" name="addEducation" data-toggle="modal" data-target="#add_modal"></i>';}
                                              $educations = $c1->getProfile()->GetEducations($c1->getID(),$c1->db_connection);
                                              if($educations!=0)
                                              {
                                                   $count=0;
                                                   foreach ($educations as $index)
                                                   {
                                                      echo'
                                                      <dt>Description : </dt>
                                                        <div class="pr_desc">
                                                          <dd>'.$index['education'].'
                                                          <br>
                                                          <div class="pd_dates">
                                                            <h4>- Start date :<h4>
                                                            <span>'.$index['start_date'].'</span>
                                                            <h4>- End date :<h4>
                                                            <span>'.$index['end_date'].'</span>
                                                          </div></dd>
                                                        </div>
                                                        <div class="clearfix"></div><br>';
                                                      if($client->getID()==$id)
                                                      {
                                                          echo 
                                                          '<div class="pr_Btns">
                                                            <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['education'].'\',\'education\')" value="'.$index['education'].'" type="button" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                            <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['education'].'\',\'education\')" value="'.$index['education'].'" type="button" name="delEducation"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                          </div><br>
                                                          <hr>';
                                                      }                                                        
                                                   }
                                              }
                                              else{
                                                  echo '<h5> You have not any educations </h5>';

                                              }
                                              ?>
                                          </div>
                                      </div>
                                  </div>

                    <!-- Experiences -->
                      <div class="pr_dataSquare">
                        <buton class="pr_dataBtn pr_dataBtnMid btn" data-toggle="collapse" data-target="#experienceList"><i class="fa fa-bars" aria-hidden="true"></i><span>Experiences</span></buton>
                        <div class="pr_dataContent collapse" id="experienceList">
                                <div id="experiences">
                                    <?php
                                    if($client->getID()==$id)
                                    {
                                        echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'experience\')" name="addExperience" data-toggle="modal" data-target="#add_modal"></i>';
                                    }
                                    $experiences = $c1->getProfile()->GetExperiences($c1->getID(),$c1->db_connection);

                                    if($experiences!=0)
                                    {
                                         $count=0;
                                         foreach ($experiences as $index)
                                         {
                                            echo'
                                            <dt>Description : </dt>
                                              <div class="pr_desc">
                                                <dd>'.$index['experience'].'
                                                <br>
                                                <div class="pd_dates">
                                                  <h4>- Start date :<h4>
                                                  <span>'.$index['start_date'].'</span>
                                                  <h4>- End date :<h4>
                                                  <span>'.$index['end_date'].'</span>
                                                </div></dd>
                                              </div>
                                              <div class="clearfix"></div><br>
                                              ';
                                            if($client->getID()==$id)
                                            {
                                                echo 
                                                '<div class="pr_Btns">
                                                    <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['experience'].'\',\'experience\')" value="'.$index['experience'].'" type="button" name="editAward" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                    <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['experience'].'\',\'experience\')" value="'.$index['experience'].'" type="button" name="delExperience"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                </div><br>
                                                <hr>';
                                            }                                              
                                         }
                                    }
                                    else{
                                        echo '<h5> You have not any experiences </h5>';

                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <!-- Languages -->
                      <div class="pr_dataSquare">
                          <buton class="pr_dataBtn pr_dataBtnTop btn" data-toggle="collapse" data-target="#languagesList"><i class="fa fa-bars" aria-hidden="true"></i><span>Languages</span></buton>
                          <div class="pr_dataContent collapse" id="languagesList">
                                    <div id="languages">
                                      <?php
                                      if($client->getID()==$id)
                                      {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'language\')" name="addLanguage" data-toggle="modal" data-target="#add_modal"></i>';}
                                        $languages = $c1->getProfile()->GetLanguages($c1->getID(),$c1->db_connection);
                                        if($languages!=0)
                                        {
                                             $count=0;
                                             foreach ($languages as $index)
                                             {
                                                echo'
                                                <dt>Language : </dt>
                                                  <div class="pr_desc">
                                                    <dd>'.$index['language'].'
                                                    <div class="pr_rate">';
                                                      $x=1;
                                                      while($x <= $index['rate'])
                                                      {
                                                        echo '<span class="fa fa-star starChecked"></span>';
                                                        $x++;
                                                      }
                                                      $x=5;
                                                      while($x > $index['rate'])
                                                      {
                                                        echo '<span class="fa fa-star starUnchecked"></span>';
                                                        $x--;
                                                      }
                                                    echo '
                                                    <div>
                                                    </dd>
                                                  </div>
                                                  <div class="clearfix"></div>
                                                  ';
                                                if($client->getID()==$id)
                                                {
                                                    echo 
                                                    '<div class="pr_Btns">
                                                        <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['language'].'\',\'language\')" type="button" name="editLanguage" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                        <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['language'].'\',\'language\')" type="button" name="delLanguage"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                      </div>
                                                      <hr>';
                                                }                                                  
                                             }
                                        }
                                        else{
                                            echo '<h5> You have not any languages </h5>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                    <!-- Skills -->
                      <div class="pr_dataSquare">
                            <buton class="pr_dataBtn pr_dataBtnTop btn" data-toggle="collapse" data-target="#skillsList"><i class="fa fa-bars" aria-hidden="true"></i><span>Skills</span></buton>
                            <div class="pr_dataContent collapse" id="skillsList">
                                            <div id="skills">
                                                <?php 
                                                if($client->getID()==$id)
                                                {echo '<i class="pr_addBtn fa fa-plus" aria-hidden="true" type="button" onclick="updAddModal(\'skill\')" name="addSkill" data-toggle="modal" data-target="#add_modal"></i>';}
                                                $skills = $c1->getProfile()->GetSkills($c1->getID(),$c1->db_connection);
                                                if($skills!=0)
                                                {
                                                     $count=0;
                                                     foreach ($skills as $index)
                                                     {
                                                        echo'
                                                            <dt>Description : </dt>
                                                            <div class="pr_desc">
                                                              <dd>'.$index['skill'].'
                                                              <div class="pr_rate">';
                                                              $x=1;
                                                              while($x <= $index['rate'])
                                                              {
                                                                echo '<span class="fa fa-star starChecked"></span>';
                                                                $x++;
                                                              }
                                                              $x=5;
                                                              while($x > $index['rate'])
                                                              {
                                                                echo '<span class="fa fa-star starUnchecked"></span>';
                                                                $x--;
                                                              }
                                                            echo '
                                                              </div></dd>
                                                            </div>
                                                                <div class="clearfix"></div>
                                                            ';
                                                            if($client->getID()==$id)
                                                            {
                                                                echo 
                                                                '<div class="pr_Btns">
                                                                    <button class="btn btn-primary pr_dataEditBtn" onclick="updUpdateModal(\''.$index['skill'].'\',\'skill\')" value="'.$index['skill'].'" class="btn btn-primary editSkill" type="button" name="editSkill" data-toggle="modal" data-target="#update_modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                                    <button class="btn btn-primary pr_dataDelBtn" onclick="DeleteModal(\''.$index['skill'].'\',\'skill\')" value="'.$index['skill'].'" class="btn btn-primary delSkill" type="button" name="delSkill"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                                                                <hr>';
                                                            }                                                            
                                                     }
                                                }
                                                else{
                                                    echo '<h5> You have not any skills </h5>';

                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                      <div id="snackbar">Delete done successfully</div>
                    </div>
                    <!-- Modal 1 [Edit Info]-->
                    <div class="modal fade" id="edit_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Edit Information</h3>
                                </div>
                                <div class="modal-body">
                                        <label>Name : </label>
                                        <input type="text" name="neme" value="<?php echo $c1->getName(); ?>" id="name" class="form-control">
                                        <label>ZIP Code : </label>
                                        <input type="text" name="zip" value="<?php echo $data[0]['zip_code']; ?>" id="zip" class="form-control">
                                        <label>Country : </label>
                                        <select name="country" id="coumtryOpt" class="form-control" onmousedown="if(this.options.length>8){this.size=8;}" onchange="this.size=1;" onblur="this.size=1;" data-placeholder="Choose a country...">
                                            <option id="defaultCountry"><?php echo $data[0]['country']; ?></option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AG">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AG">Antigua &amp; Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AA">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia</option>
                                            <option value="BL">Bonaire</option>
                                            <option value="BA">Bosnia &amp; Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BR">Brazil</option>
                                            <option value="BC">British Indian Ocean Ter</option>
                                            <option value="BN">Brunei</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="IC">Canary Islands</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CD">Channel Islands</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CI">Christmas Island</option>
                                            <option value="CS">Cocos Island</option>
                                            <option value="CO">Colombia</option>
                                            <option value="CC">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CT">Cote D'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CB">Curacao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="TM">East Timor</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FA">Falkland Islands</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="FS">French Southern Ter</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GB">Great Britain</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HW">Hawaii</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IA">Iran</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IR">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="NK">Korea North</option>
                                            <option value="KS">Korea South</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Laos</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macau</option>
                                            <option value="MK">Macedonia</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="ME">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="MI">Midway Islands</option>
                                            <option value="MD">Moldova</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Nambia</option>
                                            <option value="NU">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="AN">Netherland Antilles</option>
                                            <option value="NL">Netherlands (Holland, Europe)</option>
                                            <option value="NV">Nevis</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NW">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau Island</option>
                                            <option value="PS">Palestine</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PO">Pitcairn Island</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="ME">Republic of Montenegro</option>
                                            <option value="RS">Republic of Serbia</option>
                                            <option value="RE">Reunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russia</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="NT">St Barthelemy</option>
                                            <option value="EU">St Eustatius</option>
                                            <option value="HE">St Helena</option>
                                            <option value="KN">St Kitts-Nevis</option>
                                            <option value="LC">St Lucia</option>
                                            <option value="MB">St Maarten</option>
                                            <option value="PM">St Pierre &amp; Miquelon</option>
                                            <option value="VC">St Vincent &amp; Grenadines</option>
                                            <option value="SP">Saipan</option>
                                            <option value="SO">Samoa</option>
                                            <option value="AS">Samoa American</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome &amp; Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="OI">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syria</option>
                                            <option value="TA">Tahiti</option>
                                            <option value="TW">Taiwan</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad &amp; Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TU">Turkmenistan</option>
                                            <option value="TC">Turks &amp; Caicos Is</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States of America</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VS">Vatican City State</option>
                                            <option value="VE">Venezuela</option>
                                            <option value="VN">Vietnam</option>
                                            <option value="VB">Virgin Islands (Brit)</option>
                                            <option value="VA">Virgin Islands (USA)</option>
                                            <option value="WK">Wake Island</option>
                                            <option value="WF">Wallis &amp; Futana Is</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZR">Zaire</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                            </select>
                                        <label>Summary : </label>
                                        <textarea name="summary" id="summary" class="form-control"><?php echo $data[0]['summary'] ?></textarea>
                                        <label>Change Personal Image : </label>
                                          <!-- image -->
                                          <form id="edit_form" method="POST" action="client_profile.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                                            <label id="imgUpload" class="btn btn-md col-xs-12 change">
                                                choose Image : <input id="client_img" type="file"  name="image" onchange="document.getElementById('autoload').src = window.URL.createObjectURL(this.files[0])">
                                            </label><br>
                                            <input type="submit" name="submit_img" id="submit_img" class="btn" value="Update Image"/>
                                        </form>
                                        <!--  -->
                                        <input type="button" name="submit" id="submit" class="btn" value="Submit"/>

                                        <div id="error_message" class="text-danger"></div>
                                        <div id="success_message" class="text-success"></div>
                                </div>
                                <div class="modal-footer">
                                    <button  class="btn editPass" onclick="updPassModel()" type="button" name="editPass" data-toggle="modal" data-target="#password_modal">Change Password</button>
                                    <button type="button" class="btn closeBtn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 2 [Reset Password]-->
                    <div class="modal fade" id="password_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Change Password</h3>
                                </div>
                                <div class="modal-body">
                                    <form id="password_form">

                                        <label>Current Password : </label>
                                        <input type="password" name="currentPass" placeholder="write the current password" id="currentPass" class="form-control">
                                        <label>New Password : </label>
                                        <input type="password" name="newPass" placeholder="write the new password" id="newPass" class="form-control">
                                        <label>Retype New Password : </label>
                                        <input type="password" name="rePass" placeholder="rewrite the current password" id="rePass" class="form-control">

                                        <input type="button" name="submit_pass" id="submit_pass" class="btn" value="Save"/>

                                        <div id="pass_error_message" class="text-danger" style="margin-top: 15px"></div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn closeBtn" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 3 [Add] -->
                    <div class="modal fade" id="add_modal">
                        <div class="modal-dialog">
                            <div class="modal-content add_modal-content">
                                <div class="modal-header">
                                    <h3 id="modal_title">Add</h3>
                                </div>
                                <div class="modal-body">
                                    <form id="add_form">

                                      <div id="descSec">
                                        <label id="modal_label">Write description : </label>
                                        <textarea name="" id="modal_description" class="form-control"></textarea>
                                      </div>

                                        <div id="datesSec">
                                            <div class="input-group">
                                                <label>Select Start Date</label>
                                                <input id="startDate" value="" type="date" class="form-control" name="date">
                                            </div>
                                            <div class="input-group">
                                                <label>Select End Date</label>
                                                <input id="endDate" type="date" value="" class="form-control" name="date">
                                            </div>
                                        </div>

                                        <div id="langSec">
                                          <select class="form-control" id="langSelect" data-placeholder="Choose a Language...">
                                          <option value="Afrikanns">Afrikanns</option>
                                          <option value="Albanian">Albanian</option>
                                          <option value="Arabic">Arabic</option>
                                          <option value="Armenian">Armenian</option>
                                          <option value="Basque">Basque</option>
                                          <option value="Bengali">Bengali</option>
                                          <option value="Bulgarian">Bulgarian</option>
                                          <option value="Catalan">Catalan</option>
                                          <option value="Cambodian">Cambodian</option>
                                          <option value="Chinese (Mandarin)">Chinese (Mandarin)</option>
                                          <option value="Croation">Croation</option>
                                          <option value="Czech">Czech</option>
                                          <option value="Danish">Danish</option>
                                          <option value="Dutch">Dutch</option>
                                          <option value="English">English</option>
                                          <option value="Estonian">Estonian</option>
                                          <option value="Fiji">Fiji</option>
                                          <option value="Finnish">Finnish</option>
                                          <option value="French">French</option>
                                          <option value="Georgian">Georgian</option>
                                          <option value="German">German</option>
                                          <option value="Greek">Greek</option>
                                          <option value="Gujarati">Gujarati</option>
                                          <option value="Hebrew">Hebrew</option>
                                          <option value="Hindi">Hindi</option>
                                          <option value="Hungarian">Hungarian</option>
                                          <option value="Icelandic">Icelandic</option>
                                          <option value="Indonesian">Indonesian</option>
                                          <option value="Irish">Irish</option>
                                          <option value="Italian">Italian</option>
                                          <option value="Japanese">Japanese</option>
                                          <option value="Javanese">Javanese</option>
                                          <option value="Korean">Korean</option>
                                          <option value="Latin">Latin</option>
                                          <option value="Latvian">Latvian</option>
                                          <option value="Lithuanian">Lithuanian</option>
                                          <option value="Macedonian">Macedonian</option>
                                          <option value="Malay">Malay</option>
                                          <option value="Malayalam">Malayalam</option>
                                          <option value="Maltese">Maltese</option>
                                          <option value="Maori">Maori</option>
                                          <option value="Marathi">Marathi</option>
                                          <option value="Mongolian">Mongolian</option>
                                          <option value="Nepali">Nepali</option>
                                          <option value="Norwegian">Norwegian</option>
                                          <option value="Persian">Persian</option>
                                          <option value="Polish">Polish</option>
                                          <option value="Portuguese">Portuguese</option>
                                          <option value="Punjabi">Punjabi</option>
                                          <option value="Quechua">Quechua</option>
                                          <option value="Romanian">Romanian</option>
                                          <option value="Russian">Russian</option>
                                          <option value="Samoan">Samoan</option>
                                          <option value="Serbian">Serbian</option>
                                          <option value="Slovak">Slovak</option>
                                          <option value="Slovenian">Slovenian</option>
                                          <option value="Spanish">Spanish</option>
                                          <option value="Swahili">Swahili</option>
                                          <option value="Swedish ">Swedish </option>
                                          <option value="Tamil">Tamil</option>
                                          <option value="Tatar">Tatar</option>
                                          <option value="Telugu">Telugu</option>
                                          <option value="Thai">Thai</option>
                                          <option value="Tibetan">Tibetan</option>
                                          <option value="Tonga">Tonga</option>
                                          <option value="Turkish">Turkish</option>
                                          <option value="Ukranian">Ukranian</option>
                                          <option value="Urdu">Urdu</option>
                                          <option value="Uzbek">Uzbek</option>
                                          <option value="Vietnamese">Vietnamese</option>
                                          <option value="Welsh">Welsh</option>
                                          <option value="Xhosa">Xhosa</option>
                                        </select>
                                        </div>

                                        <div id="rate">
                                          <div class="input-group">
                                              <label>Rate</label>
                                              <br>
                                              <?php
                                                $x=1;
                                                while($x <= 5)
                                                {
                                                  echo '<a  href="javascript:setRate(\''.$x.'\')" id="star'.$x.'"> ■ </a>';
                                                  $x++;
                                                }
                                              ?>
                                          </div>
                                        </div>

                                        <input type="button" name="" id="submit_add" class="btn" value="Add"/>
                                        <div id="add_error_message" class="text-danger"></div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn closeBtn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 4 [Update] -->
                    <div class="modal fade" id="update_modal">
                        <div class="modal-dialog">
                            <div class="modal-content upd_modal-content">
                                <div class="modal-header">
                                    <h3 id="update_title"></h3>
                                </div>
                                <div class="modal-body">
                                    <form id="add_form">

                                        <div id="descSec_upd">
                                          <lable id="update_label" ></lable>
                                          <textarea name="upd_description" id="update_description" class="form-control"></textarea>
                                        </div>

                                        <div id="datesSec_upd">
                                            <div class="input-group">
                                                <label>Start Date</label>
                                                <input id="startDate_upd" type="date" class="form-control" name="date">
                                            </div>
                                            <div class="input-group">
                                                <label>End Date</label>
                                                <input id="endDate_upd" type="date" class="form-control" name="date">
                                            </div>
                                        </div>

                                        <div id="langSec_upd">
                                          <select class="form-control" id="langSelect_upd" data-placeholder="Choose a Language...">
                                          <option value="Afrikanns">Afrikanns</option>
                                          <option value="Albanian">Albanian</option>
                                          <option value="Arabic">Arabic</option>
                                          <option value="Armenian">Armenian</option>
                                          <option value="Basque">Basque</option>
                                          <option value="Bengali">Bengali</option>
                                          <option value="Bulgarian">Bulgarian</option>
                                          <option value="Catalan">Catalan</option>
                                          <option value="Cambodian">Cambodian</option>
                                          <option value="Chinese (Mandarin)">Chinese (Mandarin)</option>
                                          <option value="Croation">Croation</option>
                                          <option value="Czech">Czech</option>
                                          <option value="Danish">Danish</option>
                                          <option value="Dutch">Dutch</option>
                                          <option value="English">English</option>
                                          <option value="Estonian">Estonian</option>
                                          <option value="Fiji">Fiji</option>
                                          <option value="Finnish">Finnish</option>
                                          <option value="French">French</option>
                                          <option value="Georgian">Georgian</option>
                                          <option value="German">German</option>
                                          <option value="Greek">Greek</option>
                                          <option value="Gujarati">Gujarati</option>
                                          <option value="Hebrew">Hebrew</option>
                                          <option value="Hindi">Hindi</option>
                                          <option value="Hungarian">Hungarian</option>
                                          <option value="Icelandic">Icelandic</option>
                                          <option value="Indonesian">Indonesian</option>
                                          <option value="Irish">Irish</option>
                                          <option value="Italian">Italian</option>
                                          <option value="Japanese">Japanese</option>
                                          <option value="Javanese">Javanese</option>
                                          <option value="Korean">Korean</option>
                                          <option value="Latin">Latin</option>
                                          <option value="Latvian">Latvian</option>
                                          <option value="Lithuanian">Lithuanian</option>
                                          <option value="Macedonian">Macedonian</option>
                                          <option value="Malay">Malay</option>
                                          <option value="Malayalam">Malayalam</option>
                                          <option value="Maltese">Maltese</option>
                                          <option value="Maori">Maori</option>
                                          <option value="Marathi">Marathi</option>
                                          <option value="Mongolian">Mongolian</option>
                                          <option value="Nepali">Nepali</option>
                                          <option value="Norwegian">Norwegian</option>
                                          <option value="Persian">Persian</option>
                                          <option value="Polish">Polish</option>
                                          <option value="Portuguese">Portuguese</option>
                                          <option value="Punjabi">Punjabi</option>
                                          <option value="Quechua">Quechua</option>
                                          <option value="Romanian">Romanian</option>
                                          <option value="Russian">Russian</option>
                                          <option value="Samoan">Samoan</option>
                                          <option value="Serbian">Serbian</option>
                                          <option value="Slovak">Slovak</option>
                                          <option value="Slovenian">Slovenian</option>
                                          <option value="Spanish">Spanish</option>
                                          <option value="Swahili">Swahili</option>
                                          <option value="Swedish ">Swedish </option>
                                          <option value="Tamil">Tamil</option>
                                          <option value="Tatar">Tatar</option>
                                          <option value="Telugu">Telugu</option>
                                          <option value="Thai">Thai</option>
                                          <option value="Tibetan">Tibetan</option>
                                          <option value="Tonga">Tonga</option>
                                          <option value="Turkish">Turkish</option>
                                          <option value="Ukranian">Ukranian</option>
                                          <option value="Urdu">Urdu</option>
                                          <option value="Uzbek">Uzbek</option>
                                          <option value="Vietnamese">Vietnamese</option>
                                          <option value="Welsh">Welsh</option>
                                          <option value="Xhosa">Xhosa</option>
                                        </select>
                                        </div>

                                        <div id="rate_upd">
                                          <div class="input-group">
                                              <label>Rate : </label>
                                              <br>
                                              <?php
                                                $x=1;
                                                while($x <= 5)
                                                {
                                                  echo '<a  href="javascript:setRate_upd(\''.$x.'\')" id="upd_star'.$x.'"> ■ </a>';
                                                  $x++;
                                                }
                                              ?>
                                          </div>

                                        </div>

                                        <input type="button" name="submit_upd" id="submit_upd" class="btn" value="Save"/>
                                        <div id="upd_error_message" class="text-danger"></div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn closeBtn" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                  </div>
              </div>
          </div>

<!--                      JAVASCRIPT                  -->
<script>
  function hideElements()
  {

  }
</script>
<script> //Update [Basic Info Modal]
  function updInfoModal(c){
             document.getElementById("error_message").innerHTML="";
             document.getElementById("success_message").innerHTML="";
             document.getElementById("name").style.border="";
             document.getElementById("zip").style.border="";
             document.getElementById("summary").style.border="";
             document.getElementById("defaultCountry").innerHTML=c;
  }
  function updPassModel(){
      document.getElementById("currentPass").style.border="";
      document.getElementById("newPass").style.border="";
      document.getElementById("rePass").style.border="";

      $('#pass_error_message').html('');
  }
</script>

<script> //Data
  $(document).ready(function(){
        $('#submit').click(function(){
             var name = $('#name').val();
             var zip = $('#zip').val();
             var summary = $('#summary').val();
             var client_ID = "<?php echo $c1->getID()?>";
             var image = $('#client_img').val();
             var e = document.getElementById('coumtryOpt');
             var country = e.options[e.selectedIndex].text;
             name = name.trim();
             summary = summary.trim();
             zip = zip.trim();
             var nameRegExp = /^[a-zA-Z ]*$/;
             document.getElementById("error_message").innerHTML="";
             document.getElementById("success_message").innerHTML="";

             document.getElementById("name").style.border="";
             document.getElementById("zip").style.border="";
             document.getElementById("summary").style.border="";


                  if(summary.length<5)
                  {
                      var elem = document.getElementById("summary");
                      elem.style.border="1px solid red";
                      document.getElementById("error_message").innerHTML+="<br>Minimum number of characters is 5";
                  }
                  else if(summary.length>100)
                  {
                      var elem = document.getElementById("summary");
                      elem.style.border="1px solid red";
                      document.getElementById("error_message").innerHTML+="<br>Maximum number of characters is 100";
                  }
                  else if(name.length<3)
                  {
                      var elem = document.getElementById("name");
                      elem.style.border="1px solid red";
                      document.getElementById("error_message").innerHTML+="<br>Name should be at least 3 letters";
                  }
                  else if(!nameRegExp.test(name))
                  {
                    var elem = document.getElementById("name");
                    elem.style.border="1px solid red";
                    document.getElementById("error_message").innerHTML+="<br>Please enter a valid name (Alphabetic Only)";
                  }
                  else if((zip.toString().length)!==5 || isNaN(zip))
                  {
                      var elem = document.getElementById("zip");
                      elem.style.border="1px solid red";
                     document.getElementById("error_message").innerHTML+="<br>ZIP code not valid";
                  }

             else
             {
                  $('#error_message').html('');
                  $.ajax({
                       url:"Edit_Profile.php",
                       method:"POST",
                       data:{name:name, zip:zip, client_ID:client_ID ,country:country, summary:summary, action:"updateClientInfo"},
                       success:function(data){
                            $('#success_message').fadeIn().html(data);
                            setTimeout(function(){
                                 $('#success_message').fadeOut("Slow");
                            }, 2500);
                            $('#basicInfo').load(" #basicInfo");
                            $('#basicInfo2').load(" #basicInfo2");

                       }

                  });

             }
        });
   });
</script>

<script> //Password
  $(document).ready(function(){
      $('#submit_pass').click(function(){

           var currentPass = $('#currentPass').val();
           var newPass = $('#newPass').val();
           var rePass = $('#rePass').val();
           var clientPass = "<?php echo $c1->getPassword()?>";
           var client_ID = "<?php echo $c1->getID()?>";
           var passRegExp = /^[-a-zA-Z0-9._]*$/;
           $('#pass_error_message').html('');

           document.getElementById("currentPass").style.border="";
           document.getElementById("newPass").style.border="";
           document.getElementById("rePass").style.border="";

           if(currentPass === '' || newPass === '' || rePass === '' || !passRegExp.test(newPass))
           {
                document.getElementById("pass_error_message").innerHTML+="<br> All fields are required";

                if(currentPass === '')
                {
                    document.getElementById("currentPass").style.border="1px solid red";
                }
                if(newPass === '')
                {
                    document.getElementById("newPass").style.border="1px solid red";
                }
                if(!passRegExp.test(newPass))
                {
                  document.getElementById("newPass").style.border="1px solid red";
                  document.getElementById("pass_error_message").innerHTML="<br>You allowed to use (. , - , _ , letters , numbers)";
                }

                if(rePass === '')
                {
                    elem = document.getElementById("rePass").style.border="1px solid red";
                }
           }
           else if(clientPass !== currentPass)
           {
               document.getElementById("pass_error_message").innerHTML+="<br>The password you entered is wrong";
               document.getElementById("currentPass").style.border="1px solid red";
           }
           else if(newPass !== rePass)
              {
                  document.getElementById("pass_error_message").innerHTML+="<br>The password you entered not identical";
                  elem = document.getElementById("rePass").style.border="1px solid red";
              }
           else
           {
                $('#pass_error_message').html('');
                $.ajax({
                     url:"Edit_Profile.php",
                     method:"POST",
                     data:{currentPass:currentPass, newPass:newPass, rePass:rePass, client_ID:client_ID, action:"updateClientPass"},
                     success:function(data){
                          $('#pass_error_message').fadeIn().html(data);

                          if(data.length<3)
                          {
                              $('#password_modal').modal('hide');
                              $('#pass_error_message').html('');
                              $("form").trigger("reset");
                              window.location.href="client_profile.php?id=<?php echo $id; ?>";
                          }

                     }

                     });
           }
      });
 });
</script>

<script> //Reload
  function reloadData(type){
    if(type==='award')
        $('#awards').load(" #awards");
    if(type==='project')
        $('#projects').load(" #projects");
    if(type==='certification')
        $('#certifications').load(" #certifications");
    if(type==='experience')
        $('#experiences').load(" #experiences");
    if(type==='education')
        $('#educations').load(" #educations");
    if(type==='activity')
        $('#activities').load(" #activities");
    if(type==='skill')
      $('#skills').load(" #skills");
    if(type==='language')
      $('#languages').load(" #languages");
}
</script>

<script> //Update [Add Modal]
  function updAddModal(type){
    document.getElementById('modal_label').innerHTML="Write description for your "+type+" :";
    $('#modal_description').val("");
    document.getElementById('modal_description').setAttribute('name',type);
    document.getElementById('submit_add').setAttribute('name',type);
    document.getElementById('modal_title').innerHTML="Add "+type;
    $('#add_error_message').html('');
    document.getElementById("modal_description").style.border="";
    document.getElementById('descSec').style.display= "inline";
    document.getElementById('datesSec').style.display= "none";
    document.getElementById('rate').style.display= "none";
    document.getElementById('langSec').style.display= "none";

    if(type=="experience" || type=="activity" || type=="education")
    {
      document.getElementById('datesSec').style.display= "inline";
    }

    if(type=="language" || type=="skill")
    {
      document.getElementById('rate').style.display= "inline";
      var count;
      for(count=1 ; count<=5 ; count++)
      {
        starId = 'star'+count;
        document.getElementById(starId).style.color="grey";
      }
      if(type=="language")
      {
        document.getElementById('langSec').style.display= "inline";
        document.getElementById('descSec').style.display= "none";
      }
    }
}
</script>

<script> //Add
  var rateValue = 0;
  function setRate(n)
  {
    rateValue = n;

    var count;
    for(count=1 ; count<=n ; count++)
    {
      starId = 'star'+count;
      document.getElementById(starId).style.color="orange";
    }
    for(count2=5 ; count2>=count ; count2--)
    {
      starId = 'star'+count2;
      document.getElementById(starId).style.color="grey";
    }

  }
  $(document).ready(function(){
        $('#submit_add').click(function(){

            var type = document.getElementById('submit_add').name;
            var client_ID = "<?php echo $c1->getID()?>";

            document.getElementById("add_error_message").innerHTML="";


            if(type=='language')
            {
              var e = document.getElementById('langSelect');
              var pk = e.options[e.selectedIndex].text;

              var rate = rateValue;
              if(rate == 0)
              {
                document.getElementById("add_error_message").innerHTML+="<br>Please set a rate";
              }
             else {
               $.ajax({
                    cache:false,
                    url:"Edit_Profile.php",
                    method:"POST",
                    data:{pk_id:pk ,client_ID:client_ID, action:'add'+type,rate:rate},
                    success:function(data){
                           if(data==0)
                           {
                             document.getElementById("add_error_message").innerHTML+="<br>This language already exsists";
                           }
                           else
                           {
                             reloadData(type);
                             $('#add_modal').modal('hide');
                             $("form").trigger("reset");
                           }
                       }
                    });
             }

            }
            else {
              var pk = $('#modal_description').val();
              pk = pk.trim();

              document.getElementById("modal_description").style.border="";
              if(pk.length<5 || pk.length>200)
              {
                   if(pk.length<5)
                   {
                       var elem = document.getElementById("modal_description");
                       elem.style.border="1px solid red";
                       document.getElementById("add_error_message").innerHTML+="<br>Minimum number of characters is 5";
                   }
                   if(pk.length>200)
                   {
                       var elem = document.getElementById("modal_description");
                       elem.style.border="1px solid red";
                       document.getElementById("add_error_message").innerHTML+="<br>Maximum number of characters is 200";
                   }
              }
              else
              {
                  $('#add_error_message').html('');

                  if(type=="award" || type=="certification" || type=="project")
                  {
                    $.ajax({
                         cache:false,
                         url:"Edit_Profile.php",
                         method:"POST",
                         data:{pk_id:pk ,client_ID:client_ID, action:'add'+type},
                         success:function(data){
                                if(data==0)
                                {
                                  document.getElementById("add_error_message").innerHTML+="<br>This Information already exsists";
                                }
                                else
                                {
                                  reloadData(type);
                                  $('#add_modal').modal('hide');
                                  $("form").trigger("reset");
                                }
                            }
                         });
                  }
                  else if(type=="experience" || type=="activity" || type=="education"){

                       var startDate = $("#startDate").val();
                       var endDate = $("#endDate").val();

                       if(startDate.length!=10 || endDate.length!=10){
                          document.getElementById("add_error_message").innerHTML+="<br>Dates are required";
                        }
                      else if ( startDate>endDate ){
                        document.getElementById("add_error_message").innerHTML+="<br>Start date can't be after End date";
                      }
                       else {
                            $.ajax({
                                 cache:false,
                                 url:"Edit_Profile.php",
                                 method:"POST",
                                 data:{pk_id:pk ,client_ID:client_ID, action:'add'+type,startDate:startDate ,endDate:endDate },
                                 success:function(data){
                                        if(data==0)
                                        {
                                          document.getElementById("add_error_message").innerHTML+="<br>This Information already exsists";
                                        }
                                        else
                                        {
                                          reloadData(type);
                                          $('#add_modal').modal('hide');
                                          $("form").trigger("reset");
                                        }
                                    }
                                 });
                          }
                     }
                  else if(type=="skill"){

                    var rate = rateValue;
                    if(rate == 0)
                    {
                      document.getElementById("add_error_message").innerHTML+="<br>Please set a rate";
                    }
                   else {
                     $.ajax({
                          cache:false,
                          url:"Edit_Profile.php",
                          method:"POST",
                          data:{pk_id:pk ,client_ID:client_ID, action:'add'+type,rate:rate},
                          success:function(data){
                                 if(data==0)
                                 {
                                   document.getElementById("add_error_message").innerHTML+="<br>This Information already exsists";
                                 }
                                 else
                                 {
                                   reloadData(type);
                                   $('#add_modal').modal('hide');
                                   $("form").trigger("reset");
                                 }
                             }
                          });
                   }
                  }

              }



            }


        });
   });
 </script>

<script> //Update [Update Modal]
  var rateValue = 0;
  function setRate_upd(n)
  {
    rateValue = n;

    var count;
    for(count=1 ; count<=n ; count++)
    {
      starId = 'upd_star'+count;
      document.getElementById(starId).style.color="orange";
    }
    for(count2=5 ; count2>=count ; count2--)
    {
      starId = 'upd_star'+count2;
      document.getElementById(starId).style.color="grey";
    }

  }
    var tableName;
    var data_pk;
    function updUpdateModal(oldData,type){
     tableName=type;
     data_pk=oldData;

        document.getElementById('update_title').innerHTML="Update "+type+" :";
        document.getElementById('update_label').innerHTML="Write description for your "+type+" :";
        document.getElementById("upd_error_message").innerHTML="";
        document.getElementById("update_description").style.border="";

        document.getElementById('datesSec_upd').style.display= "none";
        document.getElementById('rate_upd').style.display= "none";
        document.getElementById('langSec_upd').style.display= "none";
        document.getElementById('descSec_upd').style.display= "inline";

        $('#update_description').val(oldData); // fill textarea with old data

        if(tableName=="experience" || tableName=="activity" || tableName=="education")
        {
          document.getElementById('datesSec_upd').style.display= "inline";
        }

        if(tableName=="language" || tableName=="skill")
        {
          document.getElementById('rate_upd').style.display= "inline";
          var count;
          for(count=1 ; count<=5 ; count++)
          {
            starId = 'upd_star'+count;
            document.getElementById(starId).style.color="grey";
          }
          if(type=="language")
          {
            document.getElementById('update_title').innerHTML="Update "+type+" : ("+data_pk+")";
            document.getElementById('descSec_upd').style.display= "none";
          }
        }
}

//Update
  $(document).ready(function(){
      $('#submit_upd').click(function(){
          var type = tableName;
          var pk = data_pk;
          var client_ID = "<?php echo $c1->getID()?>";

          var description = $('#update_description').val();
          description = description.trim();

          document.getElementById("upd_error_message").innerHTML="";
          document.getElementById("update_description").style.border="";

           if(description.length<5 || description.length>200)
           {
               if(description.length<5)
               {
                    var elem = document.getElementById("modal_description");
                    elem.style.border="1px solid red";
                    document.getElementById("upd_error_message").innerHTML+="<br>Minimum number of characters is 5";
               }
               else
               {
                   var elem = document.getElementById("modal_description");
                   elem.style.border="1px solid red";
                   document.getElementById("upd_error_message").innerHTML+="<br>Maximum number of characters is 200";

               }
            }
           else
            {
                $('#upd_error_message').html('');
                if(type=="award" || type=="certification" || type=="project")
                {
                    $.ajax({
                         cache:false,
                         url:"Edit_Profile.php",
                         method:"POST",
                         data:{pk_id:pk ,client_ID:client_ID, description:description, action:'update'+type},
                         success:function(data){
                             if(data==0)
                                {
                                  document.getElementById("upd_error_message").innerHTML+="<br>This information already exsists";
                                }
                                else
                              {
                                  reloadData(type);
                                  $('#update_modal').modal('hide');
                                  $("form").trigger("reset");
                              }}
                         });
                   }
                   else if(type=="experience" || type=="activity" || type=="education"){

                        var startDate = $("#startDate_upd").val();
                        var endDate = $("#endDate_upd").val();

                        if(startDate.length!=10 || endDate.length!=10){
                           document.getElementById("upd_error_message").innerHTML+="<br>Dates are required";
                         }
                         else if ( startDate>endDate ){
                           document.getElementById("upd_error_message").innerHTML+="<br>Start date can't be after End date";
                         }
                        else {
                             $.ajax({
                                  cache:false,
                                  url:"Edit_Profile.php",
                                  method:"POST",
                                  data:{pk_id:pk ,client_ID:client_ID, action:'update'+type, startDate:startDate , endDate:endDate , description:description},
                                  success:function(data){
                                         if(data==0)
                                         {
                                           document.getElementById("upd_error_message").innerHTML+="<br>This Information already exsists";
                                         }
                                         else
                                         {
                                           reloadData(type);
                                           $('#update_modal').modal('hide');
                                           $("form").trigger("reset");
                                         }
                                     }
                                  });
                           }
                      }
                   else if(type=="language" || type=="skill"){

                     var rate = rateValue;
                     if(rate == 0)
                     {
                       document.getElementById("upd_error_message").innerHTML+="<br>Please set a rate";
                     }
                    else {
                          $.ajax({
                               cache:false,
                               url:"Edit_Profile.php",
                               method:"POST",
                               data:{pk_id:pk ,client_ID:client_ID, action:'update'+type,rate:rate, description:description},
                               success:function(data){
                                      if(data==0)
                                      {
                                        document.getElementById("upd_error_message").innerHTML+="<br>This Information already exsists";
                                      }
                                      else
                                      {
                                        reloadData(type);
                                        $('#update_modal').modal('hide');
                                        $("form").trigger("reset");
                                      }
                                  }
                               });
                   }}


          }
      });
 });
</script>

<script>  //Delete
  function DeleteModal(pk,table)
  {
      tableName = table;
      data_pk = pk;
      var client_ID = "<?php echo $c1->getID()?>";


      $.ajax({
           cache:false,
           url:"Edit_Profile.php",
           method:"POST",
           data:{pk:data_pk ,client_ID:client_ID, action:'delete'+tableName},
           success:function(data){
                if(data.length<3)
                {
                    myFunction();
                    reloadData(tableName);
                }}

           });
  }
</script>
<script>
function myFunction() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);
}
</script>

<script>
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("pr_topBtn").style.display = "block";
    } else {
        document.getElementById("pr_topBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
</script>
<?php require './includes/footer.php'; ?>
