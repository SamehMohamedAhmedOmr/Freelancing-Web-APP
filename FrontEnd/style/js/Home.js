$(document).ready(function(){
  $(".owl-carousel").owlCarousel();
  $('.owl-prev').html("<i class='fa fa-angle-double-left'><i>");
  $('.owl-next').html("<i class='fa fa-angle-double-right'><i>");
  });
$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true ,
    autoplay:true,
    autoplayTimeout:2500,
    autoplayHoverPause:true
});
$('.play').on('click',function(){
    owl.trigger('play.owl.autoplay',[1000])
});
$('.stop').on('click',function(){
    owl.trigger('stop.owl.autoplay')
});

//show nav at specific scroll
$(window).scroll(function ()
{
    if($(this).scrollTop()>=720)
    {
        $(".navbar-inverse").addClass("navbar-fixed-top");
        $(".scrollTop").show();
    }
    else
    {
        $(".navbar-inverse").removeClass("navbar-fixed-top");
        $(".scrollTop").hide();
    }
});

//go to the top of the page when click
$(".scrollTop").click(function ()
{
    $("html,body").animate({scrollTop:0},1000);
});
// move smoothly
$('a[href*=\\#]').on('click', function(event){     
    event.preventDefault();
    $('html,body').animate({scrollTop:$(this.hash).offset().top}, 800);
});

// loader
$(window).on('load',function()
{         
    jQuery(document).ready(function() {
    setTimeout(function() {
    $("body").css("overflow-x","hidden"); 
    }, 500);
    }); 

    $(".loading").fadeOut(500 , 
    function()
    {
       $(this).parent().fadeOut(500 , 
       function()
       {
          $(this).remove(); 
       });
    } );
    $(".OverlayLoading h1").fadeOut(500);
    $(".scrollTop").hide();

});