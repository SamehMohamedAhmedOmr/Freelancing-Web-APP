
$(document).ready(function (){
    
    cat_serviceStatistics();    
    usersStatistics();
       
});

 function getRandomColor() 
{
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function cat_serviceStatistics(x)
{
    var form = $(x); // my form
    
    var from = $("[name='from']", form).val(); // from date
    var to = $("[name='to']", form).val();     // to date
    var bar_type = $("select[name='bartype']").val();
    var pie_type = $("select[name='pietype']").val();
    // validate data
    if(!from){from="2017-01-01";}
    if(!to){to="2017-12-31";}
    if(!bar_type){bar_type="bar";}
    if(!pie_type){pie_type="pie";}
    // clear canvas first
    $('#servicesBarsStats').remove(); // this is my <canvas> element
    $('.servicesBarsStatsContainer').append('<canvas id="servicesBarsStats"></canvas>');
    //clear canvas first
    $('#servicespieStats').remove(); // this is my <canvas> element
    $('.servicespieStatsContainer').append('<canvas id="servicespieStats" style="height: 280px; width: 300px;"></canvas>');
    //Ajax
    $.post
   (  
       "Ajax.php" ,
       {
           action   : "cat_serviceStatistics" , 
           from     : from,
           to       : to
       },
       function (data , status)
       {


           if(status=="success")
           {     
               // organize data
               data = $.trim(data);
               var data = data.split("//");
               var categories = data[0];
               categories=categories.split('*');
               var services = data[1];
               services=services.split('*');

               //pie charts
               var ctx3 = document.getElementById("servicespieStats").getContext('2d'); 
               var randomColor = [];
               var mychart3 = new Chart(ctx3 , 
               {
                   type: pie_type,
                  data: {
                       labels: categories,
                       datasets: [{
                           label: 'No. of Services in each category per month',
                           data: services,
                           backgroundColor: randomColor,
                           borderWidth: 2
                       }] ,
                        options: {
                         maintainAspectRatio: false,
                        }
                   }
               });

               //bars charts
               var ctx = document.getElementById("servicesBarsStats").getContext('2d'); 
               var pointBackgroundColors = [] , myChart , backgroundColor = [];;
                // first 
               Chart.defaults.global.defaultFontColor = '#792316';
               myChart = new Chart(ctx, {
               type: bar_type,
               data: {
                   labels: categories,
                   datasets: [{
                       label: 'No. of Services in each category per month',
                       backgroundColor: pointBackgroundColors,
                       borderColor: backgroundColor,
                       data: services,
                       borderWidth: 2
                   }]
               },
               options: 
               {
                   scales: 
                   {
                       yAxes: [{
                           ticks: {
                               beginAtZero:true
                           }
                       }]  ,

                       xAxes: [{
                           barPercentage: .6   
                       }]
                   },
                   title: 
                   {
                   display: true,
                   text: 'Service Statistics'
                   } ,
                    maintainAspectRatio: false,
               }
               });
               // set colors to bars and update it
               for (i = 0; i < myChart.data.datasets[0].data.length; i++) 
               {
                   if (myChart.data.datasets[0].data[i] > 1) 
                   {  pointBackgroundColors.push("rgba(235, 199, 199, .7)"); backgroundColor.push("rgb(214, 143, 143)"); } 
                   else { pointBackgroundColors.push("rgba(172, 234, 102,.2)"); backgroundColor.push("rgb(113, 202, 11)");}
               }
               myChart.update();

               // set colors to pie and run it
               for (i = 0; i < mychart3.data.datasets[0].data.length; i++) 
               {
                   randomColor.push(getRandomColor());
               }
               mychart3.update();

           }
       }
   ); 
}

function usersStatistics (x)
{
    var form = $(x); // my form
    var from = $("[name='from']", form).val(); // from date
    var to = $("[name='to']", form).val();     // to date
    // validate data
    if(!from){from="2017-01-01";}
    if(!to){to="2017-12-31";}
    
    $.post
       (  
           "Ajax.php" ,
           {
               action: "MemberStatistics" ,
               from : from ,
               to : to
           },
           function (data , status)
           {
               var months=[] , weights=[];
               
               data = $.trim(data);
               if(data)
               {
               var data = data.split("//");
               var months = data[0];
               months=months.split('*');
               var weights = data[1];
               weights=weights.split('*');
               }

               if(status=="success")
               {                    
                   var ctx = document.getElementById("clientsStatistics").getContext('2d'); 

                    // first 
                   Chart.defaults.global.hover.mode = 'index';
                   Chart.defaults.global.defaultFontColor = '#731e06';
                   var mychart2 = new Chart(ctx , 
                   {
                           type: 'line',
                           data: {
                               labels: months,
                               datasets: [{
                                   label: 'Visitor Statistics',
                                   data: weights,
                                   borderWidth: 3 ,
                                   backgroundColor: "#e0a8a7b8"
                               }]                             
                           } ,

                           options: {
                               layout: {
                                   padding: 
                                   {
                                       left: 0,
                                       right: 0,
                                       top: 0,
                                       bottom: 0 
                                   }
                               } ,
                                maintainAspectRatio: false
                           }                             
                   });
                   mychart2.update();        
               }

           }
       ); 
}