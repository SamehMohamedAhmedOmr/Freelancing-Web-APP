
$(document).ready(function ()
{ 
    "use strict";
        
    var check1=1,check2=1,check3=1,check4=1;
    var gender = $('input[name=gender]:checked').val();
    $("#username").click(function (){
        $("#AlertS1").css('visibility','visible').hide().fadeIn().addClass('hidden');
        check1=0;
    });
    $("#username").blur(function (){
        
        var name = $.trim($('#username').val());
        var regExp = /^[a-zA-Z ]*$/;
        if(name=="")
        {
            document.getElementById('errormsg1').innerText = " - please enter your name";
            $("#AlertS1").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check1=1;
        }
        else if(!regExp.test(name))
        {
            document.getElementById('errormsg1').innerText = " - please use alphabetic letters only";
            $("#AlertS1").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check1=1;
        }
        else if(name.length<3)
        {
            document.getElementById('errormsg1').innerText = " - your name should be 3 letter at least";
            $("#AlertS1").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check1=1;
        }
    });
    
    $("#mail").click(function (){
        $("#AlertS2").css('visibility','visible').hide().fadeIn().addClass('hidden');
        check2=0;
    });
    $("#mail").blur(function (){
        var mail = $.trim($('#mail').val());
        var regExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
        if(mail=="")
        {
            document.getElementById('errormsg2').innerText = " - please enter your e-mail";
            $("#AlertS2").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check2=1;
        }
        else if(!regExp.test(mail))
        {
            document.getElementById('errormsg2').innerText = " - please enter a valid e-mail";
            $("#AlertS2").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check2=1;
        }
        else
        {
            $.post
            (   "Ajax.php" , 
                {
                    Email : mail ,
                    action :"check_mail" 
                },
                function (data, status)
                {
                    data = $.trim(data);
                    console.log(data);
                    if(data==="existing email")
                    {
                        document.getElementById('errormsg2').innerText = " - this email is already register";
                        $("#AlertS2").css('visibility','visible').hide().fadeIn().removeClass('hidden');
                        check2=1;
                        
                    }
                }
            );
        }
        
    });
    
    $("#pass").click(function (){
        $("#AlertS3").css('visibility','visible').hide().fadeIn().addClass('hidden');
        check3=0;
    });
    $("#pass").blur(function (){
        var pass = $('#pass').val();
        var regExp = /^[-a-zA-Z0-9._]*$/;
        if(!regExp.test(pass))
        {
            document.getElementById('errormsg3').innerText = " - you can use ., -, _, letters, numbers and space";
            $("#AlertS3").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check3=1;
        }
    });
    
    $("#dob").click(function (){
        $("#AlertS4").css('visibility','visible').hide().fadeIn().addClass('hidden');
        check4=0;
    });
    $("#dob").change(function (){
        var dob = $('#dob').val();
        var current = ((new Date()).getFullYear())+'-'+((new Date()).getMonth()+1)+'-'+((new Date()).getDate());
        var Years = parseInt(current.substr(0,4))-parseInt(dob.substr(0,4));
        var Months = parseInt(current.substr(5,2))-parseInt(dob.substr(5,2));
        var Days = parseInt(current.substr(8,2))-parseInt(dob.substr(8,2));
 
        if(Years<15 || (Years==15 && Months<0) || (Years==15 && Months==0 && Days<0) )
        {
            document.getElementById('errormsg4').innerText = " - Please make sure that you use your real date of birth";
            $("#AlertS4").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            check4=1;
        }
    });
    
    $('input[name=gender]').change(function(){
        gender = $('input[name=gender]:checked').val();
    });
    
    
    $('#register').on("click",function()
    {
        if(check1==0 && check2==0 && check3==0 && check4==0)
        {
            var name = $.trim($('#username').val());
            var Email = $.trim($('#mail').val());
            var pass = $('#pass').val();
            var dob = $('#dob').val();
            $.post
            (   "Ajax.php" , 
                {
                    Name : name,
                    Email : Email ,
                    pass : pass ,
                    gender : gender,
                    DOB : dob ,
                    action :"signUp" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(data==="sign_success")
                    {
                        
                        $('.sucesssignup').css('visibility','visible').hide().fadeIn().removeClass('hidden');
                    }
                }
            );
        }
        else
        {
            if(check1==1)
            {
                document.getElementById('errormsg1').innerText = " - Please insure that you fill this field correctly";
                $("#AlertS1").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            }
            if(check2==1)
            {
                document.getElementById('errormsg2').innerText = " - Please insure that you fill this field correctly";
                $("#AlertS2").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            }
            if(check3==1)
            {
                document.getElementById('errormsg3').innerText = " - Please insure that you fill this field correctly";
                $("#AlertS3").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            }
            if(check4==1)
            {
                document.getElementById('errormsg4').innerText = " - Please insure that you fill this field correctly";
                $("#AlertS4").css('visibility','visible').hide().fadeIn().removeClass('hidden');
            }
            
        }
        return false;
    });
    
});