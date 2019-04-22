function contactwithowner (OwnerID,serviceid)
{
   // console.log(OwnerID +" " + CustomerID);
    var owner = OwnerID;
    var messageContent = document.getElementById("messageData").value ;
    var serviceid = serviceid;
    if(!messageContent){
       alert("this is empty field");
    }
    else{
      //  alert(messageContent);
        var action = 'contact';
        $.get
        (   "Ajax.php" , 
            {
                action :action ,
                owner: owner,
                messageContent: messageContent
            },
            function (data , status)
            {
                
                if(status=='success')
                {
                    data = $.trim(data);
                    window.location.href="viewservice.php?ser_id="+serviceid;
                }
            }
        );
    }    
}
// get xml object from active object 
function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();

	if (window.ActiveXObject)
		return new ActiveXObject("Microsoft.XMLHTTP");

	return null;
}

function update_service_view(s_id)
{
    	var xmlhttp = GetXmlHttpObject();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
                        var responseText = this.responseText;
                        var xmlDocuments = $.parseXML(responseText);
                        var tag_result =  xmlDocuments.getElementsByTagName("result")[0].childNodes[0].nodeValue;
                        if(tag_result== '')
                        {
                            console.log(tag_result);
                        }else {console.log(tag_result);}			
		}
	}
	xmlhttp.open("POST", "Ajax.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("action=seriveViewUpdate&s_id="+s_id);
}


