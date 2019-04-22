/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    $('#submitInfo').click(function(){
        var $_GET = {};

        document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
            function decode(s) {
                return decodeURIComponent(s.split("+").join(" "));
            }

            $_GET[decode(arguments[1])] = decode(arguments[2]);
        });


        var serviceName = $.trim($('#Editname').val());
        var Description = $.trim($('#EditDescription').val());
        var Duration = $.trim($('#EditDuration').val());
        var tags = $.trim($('#Edittags').val());
        var price = $.trim($('#Editprice').val());
        document.getElementById('error_msg').innerHTML="";
        document.getElementById('success_msg').innerHTML="";
        var regExp = /^[a-zA-Z ]*$/;
        var Des_regExp = /^[-a-zA-Z0-9,. ]*$/;
        var Tags_regExp = /^[-a-zA-Z ]*$/;
        var Num_regExp = /^[0-9]*$/;
        
        document.getElementById("Editname").style.border="";
        document.getElementById("EditDescription").style.border="";
        document.getElementById("EditDuration").style.border="";
        document.getElementById("Edittags").style.border="";
        document.getElementById("Editprice").style.border="";
        
        if(serviceName==='' || !regExp.test(serviceName))
        {
            var elem = document.getElementById("Editname");
            elem.style.border="1px solid red";
            if(serviceName==='')
                document.getElementById("error_msg").innerHTML+="<br>please enter the service name";
            else
                document.getElementById("error_msg").innerHTML+="<br>please use alphabetic letters only";
        }
        else if(Description==='' || !Des_regExp.test(Description))
        {
            var elem = document.getElementById("EditDescription");
            elem.style.border="1px solid red";
            if(Description==='')
                document.getElementById('error_msg').innerHTML += '<br> please enter the description';
            else
                document.getElementById('error_msg').innerHTML += '<br> please use alphabetic letters, numbers, -, . and , in your description';
        
        }
        else if (tags==='' || !Tags_regExp.test(tags))
        {
            var elem = document.getElementById("Edittags");
            elem.style.border="1px solid red";
            if(tags==='')
                document.getElementById('error_msg').innerHTML += '<br> please enter tages that describe your service';
            else
                document.getElementById('error_msg').innerHTML += '<br> please use alphabetic letters and separat it by - in tags field';
        
        }
        else if (Duration==='' || !Num_regExp.test(Duration))
        {
            var elem = document.getElementById("EditDuration");
            elem.style.border="1px solid red";
            if(tags==='')
                document.getElementById('error_msg').innerHTML += '<br> please write your service duration';
            else
                document.getElementById('error_msg').innerHTML += '<br> please use numbers only in duration field';
        
        }
        else if (price==='' || !Num_regExp.test(price))
        {
            var elem = document.getElementById("Editprice");
            elem.style.border="1px solid red";
            if(tags==='')
                document.getElementById('error_msg').innerHTML += '<br> please write your service price';
            else
                document.getElementById('error_msg').innerHTML += '<br> please use numbers only in price field';
        
        }
        else
        {
            $.post
            (   "Ajax.php" , 
                {
                    serviceName : serviceName ,
                    Description : Description ,
                    tags : tags ,
                    Duration : Duration ,
                    price : price ,
                    s_id : $_GET['ser_id'],
                    action :"EditService" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    console.log(data);
                    if(data)
                    {
                        $('#success_msg').fadeIn().html('updated successfully');
                            setTimeout(function(){
                                 $('#success_msg').fadeOut("Slow");
                            }, 2500);
                        $('#USN').load(" #USN");
                        $('#SN').load(" #SN");
                        $('#updateTags').load(" #updateTags");
                        $('#updateDuration').load(" #updateDuration");
                        $('#updateDes').load(" #updateDes");
                    }
                }
            );
        }

    });
});