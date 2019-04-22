$(document).ready(function() {  
    $('#NotificationPage').on("click", function ()
    {
         $.post
            (   "Ajax.php" , 
                {
                    action :"readNotification" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(status=="success")
                    {
                        $('.Number_of_Notification').text("0");
                    }                   
                }
            );
    });
});


