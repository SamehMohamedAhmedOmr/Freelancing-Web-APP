
$(document).ready(function (){
        //Ajax
         $.post
        (  
            "Ajax.php" ,
            {
                action: "MemberStatistics" 
            },
            function (data , status)
            {
                data = $.trim(data);
                var data = data.split("//");
                var months = data[0];
                months=months.split('*');
                var weights = data[1];
                weights=weights.split('*');

                if(status=="success")
                {                    
                    var ctx = document.getElementById("userCharts1").getContext('2d'); 
                    
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
                                    },
                                    maintainAspectRatio: false
                                } 
                            }                             
                    });
                    mychart2.update();        
                }
                
            }
        ); 

       
    
});

