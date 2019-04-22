$(document).ready(function ()
{
    // remove employee
    $('.emp_remove').on("click" , function ()
    {
        var id =  $(this).data("value");
        var element = $(this);
        var check_option = confirm("Are you sure you wanna to delete supervisor !");
        if (check_option === true) 
        {
             $.post
            (   "Ajax.php" , 
                {
                    emp_id: id ,
                    action :"remove_emp" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(status=="success"  && data=="remove_success")
                    {
                        $(element).closest('tr').fadeOut(800);
                        $('.showResult').html(' superviser remove from system successfully');
                        setTimeout(function(){
                        $('.showResult').html('');
                        }, 8000);
                    }
                    else
                    {
                       $('.showResult').html(' failed removing supervisor');
                        setTimeout(function(){
                        $('.showResult').html('');
                        }, 8000);
                    }
                }
            );
        } 
    });
    //search for employee
    $('.emp_search').on("keyup", function ()
    {
        var keyword = $('.emp_search').val();
         $.post
            (   "Ajax.php" , 
                {
                    keyword: keyword ,
                    action :"search_emp" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(status=="success")
                    {
                       $('.emp_table tbody').empty().append(data);
                    }
                }
            );
    });
});