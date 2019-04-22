
function getManagerList (x)
{
    var action = 'Mail_getStaff';
    $.post
    (   "Ajax.php" , 
        {
            action :action ,
            mgr_mail : x 
        },
        function (data , status)
        {
            
            if(status=='success')
            {
                data = $.trim(data);
                 $('.email_result').removeClass('hidden');
                if(data!="")
                {
                   
                    $('.email_result').html(data);
                } 
                else
                {
                    $('.email_result').addClass('hidden');
                }
            }
        }
    );
}

 function selectManagerMail(x)
{
    var selectedMail = $(x).html();
   $('#Email').val(selectedMail);
   $('.email_result').addClass('hidden');
};