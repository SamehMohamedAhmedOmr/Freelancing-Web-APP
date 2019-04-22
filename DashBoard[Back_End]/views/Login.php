<?php 
session_start();
if( isset($_COOKIE['E_mail']) && isset($_COOKIE['pass']))
{
    require '../config/includes.php';
    $mail = $_COOKIE['E_mail'];
    $password = $_COOKIE['pass'];
    $return_data = Employee::Dashboard_login($mail,$password,'true');
    if($return_data=='login_success')
    {       
        header("Location: DashBoard.php");
        exit();
    }    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>        
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="../style/css/bootstrap.css" >
        <link rel="stylesheet" href="../style/css/LoginStyle.css">
        <link rel="stylesheet" href="../style/css/font-awesome.min.css">
    </head>
    <body>

      <div class="container">
        <div class="row">
          <div class="formBox loginForm mx-auto mt-5">
<!-- Header -->
              <div  class="formHeader">
                  <span class="login">Login</span>
                  <i class="fa fa-chevron-circle-left backButton hidden" onclick="returnToLogin()" aria-hidden="true"></i>
                  <span class="hidden forgetHeader">Forget Password </span>
              </div>
<!-- Body -->
              <div  class="formBody">
                  <div class="welcome">
                    <span class="login">Welcome back !</span>
                  <span class="hidden forgetWelcome">Retrieve your Password</span>
                    <span role="separator" class="divider"></span>
                  </div>
                  <!-- Login Form -->
                  <form class="login">
                      <!-- username -->
                      <div class="input-group">
                        <span class="input-group-addon" id="addon1"><span class="glyphicon glyphicon-user icon" aria-hidden="true"></span></span>
                        <input type="text" class="form-control E-mail" placeholder="E-mail" aria-describedby="addon1" autocomplete="off">
                      </div>
                      <!-- password -->
                      <div class="input-group">
                        <span class="input-group-addon" id="addon2"><span class="glyphicon glyphicon-lock icon" aria-hidden="true"></span></span>
                        <input type="password" class="form-control password" placeholder="password" aria-describedby="addon2" autocomplete="off">
                      </div>
                  </form>
                  <!-- forget password form -->
                  <form class="hidden" id="forget-pass">
                      <div class="input-group">
                        <span class="input-group-addon" id="addon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                        <input type="text" class="form-control forgetMail" placeholder="Enter your Email" aria-describedby="addon1" autocomplete="off">
                      </div>
                      <div class="login-btn">
                        <button class="button"> <input type="submit" value="Send password to your mail"/> </button>
                     </div>
                  </form>
                  <!-- Remember me -->

                  <div class="checkbox login">
                    <label>
                      <input type="checkbox" value="" class="remember_me">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      <span class="cr-text"> Remember Me </span>
                    </label>
                  </div>

                  <!-- Login button -->
                  <div class="login-btn">
                    <form id="login-form" class="login">
                       <button class="button"> <input type="submit" value="Log in"/> </button>
                    </form>
                  </div>
                  
                  <div class="alert alert-warning" id="Alert1">
                    <a href="#"  class="close">&times;</a>
                    <div class="oaerror danger">
                      <strong>Error</strong><span> - You provided a wrong E-mail/password<span>
                    </div>
                  </div>

                  <div class="alert alert-warning" id="Alert2">
                    <a href="#"  class="close">&times;</a>
                    <div class="oaerror warning">
                      <strong>Error</strong><span> - Don't left a field Blank</span>
                    </div>
                  </div>
                  
                  <div class="alert alert-warning" id="Alert3">
                    <a href="#"  class="close">&times;</a>
                    <div class="oaerror danger">
                        <strong>Error</strong><span class="alert3_text"> -This E-mail Not Register yet </span>
                    </div>
                  </div>
                  
                   <div class="alert alert-warning" id="Alert4">
                    <a href="#"  class="close">&times;</a>
                    <div class="oaerror success">
                      <strong><i class="fa fa-check-circle-o" aria-hidden="true"></i></strong><span> - Email Send Successfully </span>
                    </div>
                  </div>

              </div>
<!-- Footer -->
              <div class="formFooter">
                  <span onclick="forgetPassword()" class="login"><a class="forg-pass" href="#">Forget Password! </a></span>
                  <span onclick="returnToLogin()"><a class="hidden login-footer" href="#">Login Now</a></span>
              </div>
        </div> <!-- End of formBox -->

        <div class="rights">
          <h5>Copyright © Our Website 2017</h5>
        </div>
        
     </div>
    </div>

    <script src="../style/js/jquery-3.2.1.min.js"></script>
    <script src="../style/js/bootstrap.min.js"></script>
    <script src="../style/js/Login.js"></script>
    </body>
</html>
