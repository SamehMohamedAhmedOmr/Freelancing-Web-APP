<?php
 require './includes/header.php';
        /***********  Facebook SDK PHP V5.0  ******************/
        /*****************************************************/

require_once '../API_lib/php-graph-sdk-5.5/src/Facebook/autoload.php'; // change path as needed
//make facebook object and give it ip&secret of applocation
$fb = new \Facebook\Facebook([
  'app_id' => '1444214342301006',
  'app_secret' => 'bfb424245af617ca299533580394c684',
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$signUp =   $helper->getLoginUrl('http://localhost/Mis-Sw_Proj/FrontEnd/views/facebook_callback.php?do=signup', $permissions);

 if(isset($_GET['code']))
 {
     $data = $client->updateClientStatus($_GET['code']);
     if($data)
     {
         $data=$data[0];
         echo '<span class="label label-success text-center" style="font-size:16px;display: table;
                     margin: 10px auto;">Welcome '.$data['c_name'].' , your account has been activated sucessfully , <a href="login.php">loginNow</a> </span>';
     }
 }
 ?>
<script>
        //java script sdk
      window.fbAsyncInit = function() {
        FB.init({
          appId            : '1444214342301006',
          autoLogAppEvents : true,
          xfbml            : true,
          version          : 'v2.11'
        });
        FB.AppEvents.logPageView();
      };
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));


</script>

<div class="containern">
    <div class="row">
        <?php
        if(isset($_SESSION['signuperror'])){echo $_SESSION['signuperror'];unset($_SESSION['signuperror']);}
        if(isset($_SESSION['signupsuccess'])){echo $_SESSION['signupsuccess'];unset($_SESSION['signupsuccess']);}
        ?>
        <h2 class="text-success text-center sucesssignup hidden">Sign up successfully , confirmation message was send to your mail </h2>
        <div class="ThirdSectionHeader loginHeader">
                <h2>Sign Up Page</h2>
        </div>
        <form class="loginForm" style="margin: 0px auto;width: 50%;" >
            <!-- Name -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon" >Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <input class="form-control" type="text" name="Name"  placeholder="Pls Enter your name" id="username">
            </div>
            <div class="tooltip2" id="AlertS1">
                <i class="fa fa-times"></i>
                <span class="tooltiptext2"><strong>Error</strong><span id="errormsg1"><span></span>
              </div>
            <br/>
            <!-- Email -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon" >E-mail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <input class="form-control" type="text" name="E-mail"  placeholder="Pls Enter your mail" id="mail">
            </div>
            <div class="tooltip2" id="AlertS2">
                <i class="fa fa-times"></i>
                <span class="tooltiptext2"><strong>Error</strong><span id="errormsg2"><span></span>
              </div>
            <br/>
            <!-- Password -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon">Password&nbsp;</div>
                <input class="form-control" type="password" name="pass" id="pass">
            </div>
            <div class="tooltip2" id="AlertS3">
                <i class="fa fa-times"></i>
                <span class="tooltiptext2"><strong>Error</strong><span id="errormsg3"></span></span>
              </div>
            <br/>
            <!-- gender -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon">gender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    <div class="form-control radioInput">
                        <input  type="radio" name="gender" value="male" checked required />&nbsp; male &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="female"/>&nbsp; female
                    </div>
            </div>
            <!-- Birth Date -->
            <div class="input-group mb-2 mb-sm-0" style="margin-top: 20px;">
                <div class="input-group-addon login-addon">Birth Date</div>
                <input class="form-control" type="date" name="dob" required id="dob"><br>
            </div>
            <div class="tooltip2" id="AlertS4">
                <i class="fa fa-times"></i>
                <span class="tooltiptext2"><strong>Error</strong><span id="errormsg4"> - Please make sure that you use your real date of birth</span></span>
              </div>
            <br/>


            <button class="form-control btn btn-danger loginButton" type="submit" style="margin-bottom: 25px;" id="register">Sign Up</button>

            <a style="margin-bottom: 80px;" class="btn btn-block btn-primary facebbokButton" href="<?php echo htmlspecialchars($signUp);?>" onclick="window.open(this.href ,'mywindow','width=650,height=500,top=100,resizable=0,left=300'); return false;window.close();">
                <i class="fa fa-facebook-official" aria-hidden="true"></i> Sign UP facebook
            </a>


        </form>
    </div>
</div>

<?php
    require './includes/footer.php';
