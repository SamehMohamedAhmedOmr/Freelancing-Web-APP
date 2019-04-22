<?php 
 require './includes/header.php';
require_once '../API_lib/php-graph-sdk-5.5/src/Facebook/autoload.php'; // change path as needed
if(isset($_GET['do'])&&($_GET['do']=='signup' || $_GET['do']=='login'))
{
    $url = $_GET['do'].'.php';
}
else
{
    header("Location: Home.php");
    exit();
}
$fb = new \Facebook\Facebook([
  'app_id' => '1444214342301006',
  'app_secret' => 'bfb424245af617ca299533580394c684',
  'default_graph_version' => 'v2.10',
  //'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['user_birthday', 'user_location', 'user_website']; // optional


  try {
    $accessToken = $helper->getAccessToken();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    $_SESSION['signuperror']='<p class="signuperror">Error : facebook login failed [check your connection]</p>';
       echo " 
       <script>
       window.opener.location = '$url';
       window.close();
       </script>
       "; 
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    $_SESSION['signuperror']='<p class="signuperror">Error : facebook login error pls try again later</p>';
    echo " 
    <script>
    window.opener.location = '$url';
    window.close();
    </script>
    "; 
    exit;
  }

  if (!isset($accessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    exit;
  }

  $_SESSION['fb_access_token'] = (string) $accessToken;

  try {
     $response = $fb->get('/me?fields=id,name,first_name,last_name,gender,locale,timezone,email,birthday,website,location', $accessToken);
     $requestPicture = $fb->get('/me/picture?redirect=false&width=9999' , $accessToken); //getting user picture
    //get user attributes and methods
    $user = $response->getGraphUser();
    //get user image
    $picture = $requestPicture->getGraphUser();
     
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    $_SESSION['signuperror']='<p class="signuperror">pls check your internt connection</p>';
        echo " 
        <script>
        window.opener.location = '$url';
        window.close();
        </script>
        "; 
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
      $_SESSION['signuperror']='<p class="signuperror">Error : facebook login error pls try again later</p>';
        echo " 
        <script>
        window.opener.location = '$url';
        window.close();
        </script>
        "; 
    exit;
  }
 
 
if($_GET['do']=='login')
{
    if($user->getEmail()==null)
    {$E_mail=$user['name'].'@facebook.com';}
    else {$E_mail=$user->getEmail();}
    $password = $user['id'];
    $check = Client::website_login($E_mail, $password, 'true' , 'fblogin');
    if($check=='login_success')
    {
        echo 
          " 
          <script>
              window.opener.location = 'Home.php';
              window.close();
          </script>
          "; 
    exit;
    }
    else
    {
        $_SESSION['signuperror']='<p class="signuperror">Wrong Email or Password <i class="fa fa-times"></i></p>';
        echo " 
        <script>
             window.opener.location = 'login.php';
             window.close();
        </script>
        ";  
        echo 'login fail'.$check;
    }
   
}
elseif($_GET['do']=='signup')
{
        $check =   $client::SignUp(0,0,0,0,0,"facebooklogin",$user , $picture);
        if($check==0)
        {
            $_SESSION['signuperror']='<p class="signuperror">please try again later <i class="fa fa-times"></i></p>';
            echo " 
            <script>
            window.opener.location = 'signup.php';
            window.close();
            </script>
            ";  
        }
        else
        {
            $_SESSION['signupsuccess']='<p class="signupsuccess">Welcome '. $user->getFirstName().' '.$user->getLastName().' thank you for registration , <a href="login.php">login now</a> <i class="fa fa-check-square" aria-hidden="true"></i></p>';
            echo " 
            <script>
            window.opener.location = 'signup.php';
            window.close();
            </script>
            "; 
        }
    



}







