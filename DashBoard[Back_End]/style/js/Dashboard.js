$(document).ready(function ()
{
   
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    
    //dashBoard toogle Data (users & items)
    $('.showDashboardData').click(function ()
    {
        $(this).toggleClass('selected').parent().next('.panel-body').toggle(400);
        if($(this).hasClass('selected'))
        {
           $(this).html('<i class="fa fa-minus"></i>'); 
        }
        else
        {
           $(this).html('<i class="fa fa-plus"></i>');  
        }
    });
});
