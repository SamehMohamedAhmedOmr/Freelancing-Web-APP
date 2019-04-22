/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 */
$(document).ready(function ()
{

var multipleFiles = [];
var check = [1,1,1,1,1,1,1];
var avilabelExtension = ['jpeg','jpg','png','gif'];
var regExp = /^[a-zA-Z ]*$/;
var Des_regExp = /^[-a-zA-Z0-9,. ]*$/;
var Tags_regExp = /^[-a-zA-Z ]*$/;
var Num_regExp = /^[0-9]*$/;
var validInfo = [0,0,0,0,0,0,0];

Array.prototype.equals = function (array) {
    // if the other array is a falsy value, return
    if (!array)
        return false;

    // compare lengths - can save a lot of time 
    if (this.length !== array.length)
        return false;

    for (var i = 0, l=this.length; i < l; i++) {
        // Check if we have nested arrays
        if (this[i] instanceof Array && array[i] instanceof Array) {
            // recurse into the nested arrays
            if (!this[i].equals(array[i]))
                return false;       
        }           
        else if (this[i] !== array[i]) { 
            // Warning - two different object instances will never be equal: {x:20} != {x:20}
            return false;   
        }           
    }       
    return true;
};
// Hide method from for-in loops
Object.defineProperty(Array.prototype, 'equals', {enumerable: false});

;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
            var label	 = input.nextElementSibling,
            labelVal     = label.innerHTML;
                
        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            if( this.files && this.files.length >= 1 )
            {
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                for (var i=0;i<this.files.length;i++)
                {
                    console.log(this.files[i].tem_name);
                   multipleFiles[i] = (this.files[i].name);
                }
                for (var i=0;i<multipleFiles.length;i++)
                {
                    var name = this.files[i].name;
                    var size = this.files[i].size;
                    var index = name.lastIndexOf('.');
                    var img_extension = name.substr(index+1,name.length-index+1);
                    img_extension = img_extension.toLowerCase();
                    var checkExtension = avilabelExtension.indexOf(img_extension);
                    if (checkExtension === -1)
                    {
                        document.getElementById('msg7').innerText = ' - the photo you entered is not vaild ('+name+')';
                        $('#AlertAS7').css('visibility','visible').hide().fadeIn().removeClass('hidden');
                        check[6]=1;
                            break;
                    }
                    else
                    {
                        $('#AlertAS7').css('visibility','visible').hide().fadeIn().addClass('hidden');
                        check[6]=0;
                    }
                    if(size>2097152 || size===0)
                    {
                        document.getElementById('msg7').innerText = ' - image can\'t be more than 2 MB ('+name+')';
                        $('#AlertAS7').css('visibility','visible').hide().fadeIn().removeClass('hidden');
                        check[6]=1;
                            break;
                    }
                    else
                    {
                        $('#AlertAS7').css('visibility','visible').hide().fadeIn().addClass('hidden');
                        check[6]=0;
                    }

                }
                if(validInfo.equals(check)===true)
                {
                    $('#addservice').removeAttr('disabled');
                    //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
                }
                
            }

            if( fileName )
                label.querySelector( 'span' ).innerHTML = fileName;
            else
                label.innerHTML = labelVal;
            
            

        });

        // Firefox bug fix
        input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
        input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
});
}( document, window, 0 ));

$('#serviceName').change(function (){
    var serviceName = $.trim($('#serviceName').val());
    if(serviceName==='' || !regExp.test(serviceName))
    {
        if(serviceName==='')
            document.getElementById('msg1').innerText = ' - please enter the service name';
        else
            document.getElementById('msg1').innerText = ' - please use alphabetic letters only';
        $('#AlertAS1').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[0]=1;
    }
    else 
    {
        $('#AlertAS1').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[0]=0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
        //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
    }
    
    });
    
    
    
    $('#Description').change(function (){
    var Description = $.trim($('#Description').val());
    if(Description==='' || !Des_regExp.test(Description))
    {
        if(Description==='')
            document.getElementById('msg2').innerText = ' - please enter the description';
        else
            document.getElementById('msg2').innerText = ' - please use alphabetic letters, numbers, -, . and ,';
        $('#AlertAS2').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[1]=1;
    }
    else
    {
        $('#AlertAS2').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[1]= 0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
        //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
    }
    
    });
    
    
    
    
    $('#category').change(function (){
    var category = $('#category').val();
    if (!category)
    {
        document.getElementById('msg3').innerText = ' - please select a category';
        $('#AlertAS3').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[2]=1;
    }
    else
    {
        $('#AlertAS3').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[2]= 0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
        //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
    }
    
    });
    
    
    
    
    $('#tags').change(function (){
    var tags = $.trim($('#tags').val());
    if (tags==='' || !Tags_regExp.test(tags))
    {
        $('#tagsInfo').css('visibility','visible').hide().fadeIn().addClass('hidden');
        if(tags==='')
            document.getElementById('msg4').innerText = ' - please enter tages that describe your service';
        else
            document.getElementById('msg4').innerText = ' - please use alphabetic letters and separat it by -';
        $('#AlertAS4').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[3]=1;
    }
    else
    {
        $('#AlertAS4').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[3] = 0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
        //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
    }
    
    });
    
    
    
    
    $('#Duration').change(function (){
    var Duration = $.trim($('#Duration').val());
    if (Duration==='' || !Num_regExp.test(Duration))
    {
        if(Duration==='')
            document.getElementById('msg5').innerText = ' - please fill this field';
        else 
            document.getElementById('msg5').innerText = ' - please use numbers only';
        $('#AlertAS5').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[4]=1;
    }
    else
    {
        $('#AlertAS5').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[4]= 0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
        //addService(serviceName,Description,category,tags,Duration,price,multipleFiles);
    }
    
    });
    

    
    
    $('#price').change(function (){
    var price = $.trim($('#price').val());
    if (price==='' || !Num_regExp.test(price))
    {
        if(price==='')
            document.getElementById('msg6').innerText = ' - please fill this field';
        else 
            document.getElementById('msg6').innerText = ' - please use numbers only';
        $('#AlertAS6').css('visibility','visible').hide().fadeIn().removeClass('hidden');
        check[5]=1;
    }
    else
    {
        $('#AlertAS6').css('visibility','visible').hide().fadeIn().addClass('hidden');
        check[5]= 0;
    }
    if(validInfo.equals(check)===true)
    {
        $('#addservice').removeAttr('disabled');
    }
    
    });
    
    
});
