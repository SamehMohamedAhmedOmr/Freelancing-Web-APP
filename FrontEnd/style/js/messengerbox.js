$(document).ready(function () {

    $("#usermsg").keypress(function (e) {
        if(e.which == 13) {
            //submit form via ajax, this is not JS but server side scripting so not showing here
            var message = $(this).val().trim();
            var receiverID = $('#messageData').attr('name');
            if(message!=""){
	            addMessage(receiverID,message);
	            $(this).val("");
	            newChat(receiverID);
	            e.preventDefault();
            }
        }
    });

$(".ShowMessage").animate({ scrollTop: $(this).height() }, "fast");

});

setInterval(
	function(){
	 	$(".ShowMessage").animate({ scrollTop: $(this).height() }, "fast");
  		}, 3000);


function addMessage(receiverID,Message) {
	$.get
        (   "Ajax.php" , 
            {
                owner: receiverID,
                messageContent: Message,
                action: "contact" 
            },
            function (data , status)
            {
                data = $.trim(data);

                if(status=="success")
                {
                  if(data=="success"){
                      alert("Edit Successfully");
                  }
                  else{
                    // 
                  }
                   // console.log(data);
                }
                else
                {
                    console.log('failed');
                }
            }
        );
}

function newChat(receiverID) {

         $.get
        (   "Ajax.php" , 
            {
                receiverID: receiverID ,
                action: "openNewChat" 
            },
            function (data , status)
            {
                data = $.trim(data);
                // test

                if(status=="success")
                {
                  if(data=="success"){
                      alert("Edit Successfully");
                  }
                  else{
                    $('.chatdiving').html(data);
                  }
                   // console.log(data);
                }
                else
                {
                    console.log('failed');
                }
            }
        ); 
}