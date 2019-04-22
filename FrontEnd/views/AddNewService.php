<?php
require './includes/header.php';
if(isset($_SESSION["Client"]) && $client->getRegStatus() != 0)
{
    $client = unserialize($_SESSION["Client"]);
}
else {
  header("Location: Home.php");
  exit();
}
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST['serviceName']) && isset($_POST['Description']) && isset($_POST['category']) && isset($_POST['tags']) && isset($_POST['Duration']) && isset($_POST['price']) && isset($_FILES['image']) &&!empty($_FILES['image']['name'])&&
    $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE)
    {
        $serviceName = $_POST['serviceName'];
        $Description = $_POST['Description'];
        $category = $_POST['category'];
        $tags = $_POST['tags'];
        $Duration = $_POST['Duration'];
        $price = $_POST['price'];
        $len = count($_FILES['image']['name']);
        $FilesName = array();
        $FilesTmp_Name = array();
        for($i=0; $i<$len; $i++)
        {
            $FilesName[$i] = $_FILES['image']['name'][$i];
            $FilesTmp_Name[$i] = $_FILES['image']['tmp_name'][$i];
        }
        $client = unserialize($_SESSION["Client"]);

        $result = $client->addService($serviceName, $Description, $category, $tags, $Duration, $price, $FilesName, $FilesTmp_Name,$client->getID());

    }


        //echo "<script>console.log('".json_encode($image_tmp)."');</script>";

}
 else {
$result = 0;
}
?>
<div class="no-js">
<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
<div class="container">
    <div class="row">
        <div class="ThirdSectionHeader viewCategorySection" >
            <h2 style="margin: 20px auto 50px auto;">Add Service</h2>
        </div>
        <div class="PageContent" >
            <div style="width: 60%;">
                <form method="POST" action="AddNewService.php" enctype="multipart/form-data">
            <div>
                <strong>Service name: </strong>
                <input type="text" id="serviceName" name="serviceName"/>

                <div class="tooltip3" id="AlertAS1" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg1"><span></span>
                  </div>
            </div>
                <div style="height: 55px;">
                <strong>Description: </strong>
                <textarea id="Description" name="Description"></textarea>
                <div class="tooltip3" id="AlertAS2" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg2"><span></span>
                  </div>
            </div>
            <div>
                <strong>Chose Category: </strong>
                <select id="category" name="category">
                    <option disabled selected>Select Category </option>
                    <?php
                        foreach ($MainCat as $cat) {
                            echo '<option value="'.$cat['cat_id'].'">'.$cat['name'].'</option>';
                        }
                    ?>
                </select>
                <div class="tooltip3" id="AlertAS3" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg3"><span></span>
                  </div>
            </div>
            <div>
                <strong>Tags: </strong>
                <input type="text" id="tags" name="tags"/>

                <div class="tooltip3" id="tagsInfo" style="margin-top: 0px;visibility: visible;">
                <i class="fa fa-info-circle"></i>
                <span class="tooltiptext3" style="color: #fff"><strong>Note: </strong>please separate your tags by -</span>
              </div>
                <div class="tooltip3" id="AlertAS4" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg4"><span></span>
                  </div>
            </div>

            <div>
                <strong>Delivering Duration: </strong>
                <input type="text" id="Duration" name="Duration"/>
                <div class="tooltip3" id="AlertAS5" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg5"><span></span>
                  </div>
            </div>
            <div>
                <strong>Images: </strong>
                <input type="file" id="sImg" class="form-control inputfile" style="display: none;" name="image[]" data-multiple-caption="{count} files selected" multiple/>
                <label for="sImg" class="btn btn-default btn-block btn-sm btn-file" style="width: 50%;float: right;">
                    <span>Select Image</span>&nbsp;&nbsp;<i class="fa fa-file-image-o" aria-hidden="true"></i>
                </label>
                <div class="tooltip3" id="AlertAS7" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg7"><span></span>
                  </div>
            </div>
            <div>
                <strong>Price: </strong>
                <input type="text" id="price" name="price"/>
                <div class="tooltip3" id="AlertAS6" style="margin-top: 0px;">
                    <i class="fa fa-times"></i>
                    <span class="tooltiptext3"><strong>Error</strong><span id="msg6"><span></span>
                  </div>
            </div>
            <div>
                <button  id="addservice" class="btn pr_editBtn addServiceBtn" type="submit" disabled>
                <i class="fa fa-plus-square-o" aria-hidden="true"></i> Add</button>
            </div>
                </form>
                <?php
                if($result)
                {?>
                <div class="SuccessAlert" id="SuccessAlert" style="margin-top: 0px;color: #F25C27">
                <div class="oaerror danger" style="margin-top: 0px;">
                  Your service added successfully
                </div>
              </div>
               <?php }?>
        </div>
        </div>
    </div>
</div>
</div>

<?php

include './includes/footer.php';
