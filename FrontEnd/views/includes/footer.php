
    <!--Start Footer -->
    <footer>
        <div class="footerTxt">
            All copyrights &copy; 2017 are reserved to  <span> <span class="Name">I</span>nuyasha</span>
        </div>
    </footer>

    <!-- End Footer -->
    <!-- jquery -->
    <script src="../../DashBoard[Back_End]/style/js/jquery-3.2.1.min.js"></script>
    <!--Bootstrab -->
    <script src="../../DashBoard[Back_End]/style/js/bootstrap.min.js"></script>
   
    <script src="../style/js/owl.carousel.min.js"></script>
    <script src="../style/js/jquery.nicescroll.min.js"></script>
   <!-- pages js/jquery -->    
   <script>
        if(document.title==="Home")
        {
             var file1=document.createElement('script');
             file1.setAttribute("type","text/javascript");
             file1.setAttribute("src", "engine1/wowslider.js");
             document.getElementsByTagName("head")[0].appendChild(file1);
             
             var file2=document.createElement('script');
             file2.setAttribute("type","text/javascript");
             file2.setAttribute("src", "engine1/script.js");
             document.getElementsByTagName("head")[0].appendChild(file2);
             
             var Home=document.createElement('script');
             Home.setAttribute("type","text/javascript");
             Home.setAttribute("src", "../style/js/Home.js");
             document.getElementsByTagName("head")[0].appendChild(Home);
        }
    </script>
    <script src="../style/js/Login.js"></script>
    <script src="../style/js/SignUp.js"></script>
    <script src="../style/js/viewservice.js"></script>
    <script src="../style/js/shoppingCart.js"></script>
    <script src="../style/js/messengerbox.js"></script>
    
    
    <script src="../style/js/AddService.js"></script>
    <script src="../style/js/EditService.js"></script>
    
    <script src="../style/js/web_site_contact.js"></script>
    <script>
        
        //nice scroll
        $(function() {  
        $("body").niceScroll({
             zindex: 9999999 ,
             cursorcolor:"#f00",
            horizrailenabled: false // nicescroll can manage horizontal scroll
        });

        });

    </script>
</body>
</html>
<?php 
ob_end_flush();