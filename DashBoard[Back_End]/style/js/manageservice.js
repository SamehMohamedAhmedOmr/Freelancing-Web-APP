$(document).ready(function()
{
    
        //search for employee
    $('.service_search').on("keyup", function ()
    {
        var keyword = $('.service_search').val();
         $.get
            (   "Ajax.php" , 
                {
                    keyword: keyword ,
                    action :"search_service" 
                },
                function (data , status)
                {
                    data = $.trim(data);
                    if(status=="success")
                    {
                       //coloizedRow();
                       $('.service_table tbody').empty().append(data);
                       //coloizedRow();
                    }
                   
                }
            );
    });

});

// open save button to change status
function opensaveButton(x)
{
    var e = $(x);
    $($(e.closest("tr")).find("td .saveButton")).removeAttr('disabled');
}

//update status
function updateStatus(id , x)
{
    var status , colorName, fontColor ;
    e = $(x).closest("tr").find("#serviceStatus").find(":selected").text();
    if(e==="Activated"){status=1; colorName="#b5f1c5"; fontColor="#008060";}
    else {status=0;colorName="#ffa099"; fontColor="#cc0e00"; }
   //start of Ajax     
     $.get
    (   "Ajax.php" , 
        {
          serviceid: id ,
          Status: status ,
          action: "updateServiceStatus" 
        },
        function (data , status)
        {
          data = $.trim(data);
          if(status=="success" && data=="success")
          {
            console.log(data);
            $(x).closest("tr").find("td").css("background-color", colorName);
            $(x).closest("tr").find("td").css("color", fontColor);
            $(x).closest("tr").find("td .saveButton").attr('disabled','disabled');
          }
          else
          {
            console.log(data);
          }
        }
    );
}

// remove service
function removeService(service_id , x)
{
    var id =  service_id;
    var element = $(x);
    var check_option = confirm("Are you sure you wanna to delete Service !");
    if (check_option === true) 
    {
         $.get
        (   "Ajax.php" , 
            {
                serviceid: id ,
                action : "remove_service" 
            },
            function (data , status)
            {
                data = $.trim(data);
                console.log(data);
                if(status=="success"  && data=="remove_success")
                {
                    $(element).closest('tr').fadeOut(1000);
                    alert("Removed Successfully");
                }
                else
                {
                   alert("Unsuccessfull removing");
                }
            }
        );
    }          
}