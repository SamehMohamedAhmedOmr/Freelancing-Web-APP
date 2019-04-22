$(document).ready(function ()
{
    "use strict";
    // Ajax login Code Here
    var sentAlert = '';
    $("#login-form").on("submit", function () { // when submit the form it will show the dialog
        //check if form attributes is empty
        var Email = $.trim($('.E-mail').val());
        var pass = $.trim($('.password').val());
        if(Email==="" || pass ==="")
        {
            $('#Alert1').fadeOut(0);
            $('#Alert3').fadeOut(0);
            sentAlert = $("#Alert2");
            $(sentAlert).fadeIn();
        }
        else
        {
            // check ajax login code here
            $('#Alert2').fadeOut(0);
            $('#Alert3').fadeOut(0);
            var remember_option = $('.remember_me').is(':checked');
            
            $.post
            (   "Ajax.php" , 
                {
                    Email : Email ,
                    pass : pass ,
                    option : remember_option ,
                    action :"login" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(data=="login_success")
                    {
                        window.location.href = 'DashBoard.php';
                    }
                    else 
                    {
                       sentAlert = $("#Alert1");
                       $(sentAlert).fadeIn();
                       console.log(data);
                    }
                }
            );
            
        }   
        return false;
    });
    $("#forget-pass").on("submit" , function ()
    {
        var Email = $.trim($('.forgetMail').val());
        $('#Alert1').fadeOut(0);
        $('#Alert2').fadeOut(0);
        $('#Alert3').fadeOut(0);
        $('#Alert4').fadeOut(0);
        if(Email==="")
        {
            sentAlert = $("#Alert2");
            $(sentAlert).fadeIn();
        }
        else
        {
            // check ajax login code here
            $.post
                ("Ajax.php", 
                    {
                        Email : Email ,
                        action :"forget_password" 
                    },
                    function (data , status)
                    {
                        data = $.trim(data);
                        if(data=="Mail_Sent" && status=='success')
                        {
                            sentAlert = $("#Alert4");
                            console.log('send , data = '+ data);
                            $(sentAlert).fadeIn();
                        }
                        else if(data=="Mail_Not_Found" && status == 'success')
                        {
                            $(".alert3_text").text(' - mail not found');
                            sentAlert = $("#Alert3");
                            console.log('mail not found , data = '+ data);
                            $(sentAlert).fadeIn();
                        }
                        else 
                        {
                            $(".alert3_text").text(' - pls try again later');
                            console.log(data);
                            sentAlert = $("#Alert3");
                            $(sentAlert).fadeIn();
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

function forgetPassword()
{
    $('.login').fadeOut(0);
    $('#Alert1').fadeOut(0);
    $('#Alert2').fadeOut(0);
    $('#Alert3').fadeOut(0);
    $('#Alert4').fadeOut(0);
    $('.backButton').css('visibility','visible').hide().fadeIn().removeClass('hidden');  
    $('.forgetHeader').css('visibility','visible').hide().fadeIn().removeClass('hidden');  
    $('.forgetWelcome').css('visibility','visible').hide().fadeIn().removeClass('hidden');  
    $('#forget-pass').css('visibility','visible').hide().fadeIn().removeClass('hidden');   
    $('.login-footer').css('visibility','visible').hide().fadeIn().removeClass('hidden');  
}

function returnToLogin()
{
    $('#Alert1').fadeOut(0);
    $('#Alert2').fadeOut(0);
    $('#Alert3').fadeOut(0);
    $('#Alert4').fadeOut(0);
    $('.backButton').fadeOut(0);
    $('.forgetHeader').fadeOut(0); 
    $('.forgetWelcome').fadeOut(0);
    $('#forget-pass').fadeOut(0);  
    $('.login-footer').fadeOut(0);  
    $('.login').fadeIn(0);
}
    