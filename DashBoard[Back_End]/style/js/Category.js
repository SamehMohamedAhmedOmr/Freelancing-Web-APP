$(document).ready(function()
{
    "use strict";
    //enable confirmation plugin
    $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    });
    
    $('#Edit').click(function ()        
        {
            if (document.getElementById("Edit").value == "Edit") {
               hide('#visorField');
               show('#selectVisor'); 
               document.getElementById("nameField").readOnly = false;  
               document.getElementById("desField").readOnly = false;      
               document.getElementById("orderingField").readOnly = false; 
               document.getElementById("Edit").value = "Save Changes";
               show('#changeVisiblitydiv');
               var check = document.getElementById("insert").name;
               if (check !=0) {
                  hide('#parentdiv');
                  show('#selectParent'); 
                  show('#change'); 
               }
            }
            else{
                SaveData();
            }
        });
    $('#check').change(function ()        
    {
        if (document.getElementById("check").checked) {
              hide('#selectParent'); 
        }
        else{
              show('#selectParent'); 
        }
    });
}); 


function SaveData() {

    var id = document.getElementById("Edit").name ;
    var name = document.getElementById("nameField").value ;
    var descripiton = document.getElementById("desField").value ;
    var order = document.getElementById("orderingField").value ;
    var supervisorId = document.getElementById("selectboxSuper").value;
    var parent;
    if (document.getElementById("check")) 
    {
        if (document.getElementById("check").checked) {
            parent = 0;
        }
        else{
            parent = document.getElementById("selectboxCat").value;
        }
    }
    else{
        parent = 0;
    }

    var visibility = document.getElementById("changeVisiblity").getAttribute("data-id");
    if (document.getElementById("changeVisiblity").checked) {
        if (visibility==1) {visibility=0;}
        else{visibility=1};
    }

         $.get
        (   "Ajax.php" , 
            {
                cat_id: id ,
                name: name ,
                des: descripiton ,
                order: order ,
                supervisorID: supervisorId ,
                parent: parent ,
                visible: visibility ,
                action: "updateCategory" 
            },
            function (data , status)
            {
                data = $.trim(data);
                // test

                if(status=="success")
                {
                  if(data=="success"){
                      alert("Edit Successfully");
                      window.location.href = 'ManageCategory.php';
                  }
                  else{
                    $('#showERROR').html(data);
                  }
                    console.log(data);
                }
                else
                {
                    console.log('failed');
                }
            }
        ); 
}

function show($id)
{
    $($id).css('visibility','visible').hide().fadeIn().removeClass('hidden');   
}

function hide($id)
{
    $($id).fadeOut(0);
}

// Delete Main Category
function deleteMainCategory ()      
{
    var id = $(this)[0].getAttribute('data-id');
    var element = $(this);
    $.get
   (   "Ajax.php" , 
       {
           cat_id: id ,
           action: "deleteMainCategory" 
       },
       function (data , status)
       {
           data = $.trim(data);
           if(status=="success" && data =="remove_success")
           {
               // Delete the nearest h3
               $(element).closest('div').fadeOut(800);
               $('.showResult').prepend('<p class="input_validate_sucess">Category removed successfully <i class="fa fa-check-square icon" aria-hidden="true"></i></p>');
               setTimeout(function(){
               }, 8000);
           }
           else
           {
              $('.showResult').prepend('<p class="input_validate_error">Failed removing category<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>');
               setTimeout(function(){
               }, 8000);
           }
       }
   );
}   
 // delete sub category   
function deleteSubCategory ()      
{
    var id = $(this)[0].getAttribute('data-id');
    var element = $(this);    
    $.get
   (   "Ajax.php" , 
       {
           cat_id: id ,
           action: "deleteSubCategory" 
       },
       function (data , status)
       {
           data = $.trim(data);
           if(status=="success" && data =="remove_success")
           {
               // Delete the nearest li
               $(element).closest('li').fadeOut(800);
               $('.showResult').prepend('<p class="input_validate_sucess">Category removed successfully <i class="fa fa-check-square icon" aria-hidden="true"></i></p>');
               setTimeout(function(){
               }, 8000);
           }
           else
           {
              $('.showResult').prepend('<p class="input_validate_error">Failed removing category<i class="fa fa-times-circle icon" aria-hidden="true"></i> </p>');
               setTimeout(function(){
               }, 8000);
           }
       }
   );            
}