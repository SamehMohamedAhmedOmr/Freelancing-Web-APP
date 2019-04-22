$(document).ready(function ()
{
    $('.remove_customer').on("click", function ()
    {
       var id = $(this).attr('data-id');
       var element = $(this);
       var image = $(this).closest('tr').find('td img').attr('src');
        var check_option = confirm("Are you sure you wanna to delete customer \n\All data and services about this customer will be deleted !");
        if(check_option==true)
        {
            $.post
            (   "Ajax.php" , 
                {
                    client_id: id ,
                    client_img:image ,                    
                    action :"customer_remove" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(status=="success")
                    {
                        console.log(data);
                        $(element).closest('tr').fadeOut(1000);
                        $('.showResult').html('<p class="input_validate_sucess">customer removed sucessdully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>');
                        setTimeout(function(){
                        $('.showresult').html('<p class="input_validate_sucess">customer removed sucessdully<i class="fa fa-check-square icon" aria-hidden="true"></i></p>');
                        }, 4000);
                    }
                    else
                    {
                        console.log('no');
                    }
                }
            );
        }
    });
});
