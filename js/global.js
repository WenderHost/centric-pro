jQuery(function( $ ){
  /*
  if( ! $('body').hasClass('single-epkb_post_type_1') && ! $('body').hasClass('tax-epkb_post_type_1_category') && ! $('body').hasClass('page-knowledge-base') ){
    $(".site-header").after('<div class="bumper"></div>');
  	$(window).scroll(function () {
  	  if ($(document).scrollTop() > 1 ) {
  	    $('.site-header').addClass('shrink');
  	  } else {
  	    $('.site-header').removeClass('shrink');
  	  }
  	});
  }
  /**/

    $("header .genesis-nav-menu").addClass("responsive-menu").before('<div id="responsive-menu-icon"></div>');

    $("#responsive-menu-icon").click(function(){
    	$("header .genesis-nav-menu").slideToggle();
    });

    $(window).resize(function(){
    	if(window.innerWidth > 600) {
    		$("header .genesis-nav-menu").removeAttr("style");
    	}
    });
});