
function view_inbox(search=0)
{
    $('.searchMailBox').attr('data-type', 'reciever'); 
    $('.mailBox_header').empty().append("<tr><th >Sender</th><th >message</th><th >Date</th></tr>");
    $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"view_inbox" 
           },
           function (data , status)
           {
               data = $.trim(data);
               if(status=="success")
               {
                  $('.mailbox').empty().append(data);
               }
           }
       );
}
    
function view_sendMessages(search=0)
{
    $('.searchMailBox').attr('data-type', 'sender'); 
    $('.mailBox_header').empty().append("<tr><th >To</th><th >message</th><th >Date</th></tr>");
     $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"send_msg" 
           },
           function (data , status)
           {
               
               data = $.trim(data);
               if(status=="success")
               {
                  $('.mailbox').empty().append(data);
               }
           }
       );
}

function viewSpecificMessage(sender , reciever , date)
{
    window.location.href = 'messageBox.php?sender='+sender+'&reciever='+reciever+'&date='+date;
}

function searchMailBox (mail)
{
    var type =  $('.searchMailBox').attr('data-type'); 
    if(type=="reciever")
    {
        view_inbox(mail);
    }
    else
    {
        view_sendMessages(mail);
    }
}

