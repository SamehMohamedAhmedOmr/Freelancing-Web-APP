$(document).ready(function ()
{
    "use strict";
    // Ajax login Code Here
    var sentAlert = '';
    $("#normalLogin").on("click", function () { // when submit the form it will show the dialog
        //check if form attributes is empty
        var Email = $.trim($('#E-mail').val());
        var pass = $.trim($('#pass').val());
        console.log(Email);
        console.log(pass);
        if(Email=="" || pass =="")
        {
            document.getElementById('errormsg').innerText = " - Don't left a field Blank";
            sentAlert = $("#Alert").css('visibility','visible').hide().fadeIn().removeClass('hidden');
        }
        else
        {
            // check ajax login code here
            var remember_option = $('.remember_me').is(':checked');
            console.log(remember_option);
            $.post
            (   "Ajax.php" , 
                {
                    Email : Email ,
                    pass : pass ,
                    option : remember_option ,
                    action :"client_login" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(data==="login_success")
                    {
                        window.location.href = 'Home.php';
                    }
                    else 
                    {
                        document.getElementById('errormsg').innerText = " - You provided a wrong E-mail/password";
                        sentAlert = $("#Alert").css('visibility','visible').hide().fadeIn().removeClass('hidden');
                        console.log(data);
                    }
                }
            );
            
        }   
        return false;
    });
    
    //close Error in login or forget password
    $('.close').click(function ()
    {
        sentAlert.fadeOut(); 
    });    
});
