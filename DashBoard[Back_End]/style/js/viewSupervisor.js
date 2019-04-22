/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        function myFunction() {
            document.getElementById("salary").readOnly = false;
            document.getElementById("hours").readOnly = false;
            document.getElementById("edit").disabled=true;
            document.getElementById("save").disabled=false;
            $('#save').removeClass('hidden');
            $('[data-toggle="tooltip"]').tooltip(); 
        }