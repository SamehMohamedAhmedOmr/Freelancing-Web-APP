 function AddtoChart(id ,  serviceID , date )
{

    $.post
    (   "Ajax.php" ,
        {
            client_id : id ,
            service_id : serviceID ,
            date : date ,
            action :"addToshoppingCart"
        },
        function (data)
        {
            data = $.trim(data);
            if(data==="1" || data==="0")
            {
                $('html, body').animate({scrollTop:0}, 900);
                $('.shoppingCartAdd').css('visibility','visible').hide().fadeIn().removeClass('hidden');
                $('.shoppingCartAdd').delay(5000).fadeOut();
            }
        }
    );
}
