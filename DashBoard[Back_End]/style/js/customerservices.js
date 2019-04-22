var key = null;

function view_Supervisorinbox(search=0)
{
    key="inbox";
    $('.searchMailBox').attr('data-type', 'reciever'); 
    $('.mailBox_header').empty().append("<tr><th >Sender</th><th >message</th><th >Date</th></tr>");
    $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"view_Supervisorinbox" 
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
    
function view_SuperviorSendMessages(search=0)
{
    key="recieveing_Msg";
    $('.searchMailBox').attr('data-type', 'sender'); 
    $('.mailBox_header').empty().append("<tr><th >To</th><th >message</th><th >Date</th></tr>");
     $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"supervisor_customer_msg" 
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

function view_servicesAccepted(search=0)
{
    key="Accepted";
    $('.searchMailBox').attr('data-type', 'sender'); 
    var header = "<tr> <th> To Customer </th> <th >message</th> <th >Date</th></tr>";
    $('.mailBox_header').empty().append(header);
     $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"service_Accepted" 
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



function view_unacceptedServices(search=0)
{
    key="UnAccepted";
    $('.searchMailBox').attr('data-type', 'sender'); 
    var tableHeader = "<tr> <th>To Customer</th> <th>message</th> <th>Date</th> </tr> "
    $('.mailBox_header').empty().append(tableHeader);
     $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"service_unAccepted" 
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


function viewReport(search=0)
{
    key="Report";
    $('.searchMailBox').attr('data-type', 'sender'); 
    var tableHeader = "<tr> <th>User</th> <th>Service</th> <th>Report Cause</th> <th>Date</th> </tr> "
    $('.mailBox_header').empty().append(tableHeader);
     $.post
       (   "Ajax.php" , 
           {
               search: search ,
               action :"viewReport" 
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


function viewSpecificManagerMessage(sender , reciever , date,type=0)
{
    window.location.href = 'customer_services.php?sender='+sender+'&reciever='+reciever+'&date='+date+'&type='+type;
}

function searchbox (mail)
{
    if(key=="inbox" || key==null)
    {
        view_Supervisorinbox(mail);
    }
    else if(key=="recieveing_Msg")
    {
        view_SuperviorSendMessages(mail);
    }
    else if(key=="Accepted")
    {
        view_servicesAccepted(mail);
    }
    else if(key=="UnAccepted")
    {
        view_unacceptedServices(mail);
    }
    else if(key=="Report")
    {
        viewReport(mail);
    }
}

