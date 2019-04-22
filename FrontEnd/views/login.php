<?php
require './includes/header.php'; 
require_once '../API_lib/php-graph-sdk-5.5/src/Facebook/autoload.php'; // change path as needed
//make facebook object and give it ip&secret of applocation
$fb = new \Facebook\Facebook([
  'app_id' => '1444214342301006',
  'app_secret' => 'bfb424245af617ca299533580394c684',
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$login =   $helper->getLoginUrl('http://localhost/Mis-Sw_Proj/FrontEnd/views/facebook_callback.php?do=login', $permissions);

if(isset($_SESSION['signuperror']))
{
    echo $_SESSION['signuperror'];
    unset($_SESSION['signuperror']);
}
?>
<div class="containern">
    <div class="row">
        <div class="ThirdSectionHeader loginHeader">
                <h2>Login Page</h2>
        </div>
        <form class="loginForm" style="margin: 0px auto;width: 50%;" >
            <!-- Errors -->
            <div class="alert alert-warning" id="Alert" hidden>
            <a href="#"  class="close">&times;</a>
            <div class="oaerror danger">
              <strong>Error</strong><span id="errormsg" ><span>
            </div>
          </div>

            <!-- Email -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon" >E-mail &nbsp;&nbsp;&nbsp;&nbsp;</div>
                <input class="form-control" type="text" name="E-mail"  placeholder="Pls Enter you mail" id="E-mail">
            </div>
            <br/>   
            <!-- Password -->
            <div class="input-group mb-2 mb-sm-0">
                <div class="input-group-addon login-addon">Password</div>
                <input class="form-control" type="password" name="pass" id="pass">
            </div>
            <br/>
            
            <!-- Remember me -->
            <div class="checkbox login">
              <label>
                <input type="checkbox" value="" class="remember_me">
                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                <span class="cr-text"> Remember Me </span>
              </label>
            </div>
            
            <button class="form-control btn btn-danger loginButton" type="submit" style="margin-bottom: 25px;" id="normalLogin">Sign IN</button>
            <a style="margin-bottom: 80px;" class="btn btn-block btn-primary facebbokButton" href="<?php echo htmlspecialchars($login);?>" onclick="window.open(this.href ,'mywindow','width=650,height=500,top=100,resizable=0,left=300'); return false;window.close();">
                <i class="fa fa-facebook-official" aria-hidden="true"></i> Login  Facebook
            </a>
            
        </form>
        
                  
    </div>
</div>
<?php
    require './includes/footer.php';