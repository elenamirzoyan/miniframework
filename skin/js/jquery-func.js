function _init_slider(carousel) {
	$('#slider-nav a').bind('click', function() {
		var index = $(this).parent().find('a').index(this);
		carousel.scroll( index + 1);
		return false;
	});
};

function _active_slide(carousel, item, idx, state) {
	var index = idx-1;
	$('#slider-nav a').removeClass('active');
	$('#slider-nav a').eq(index).addClass('active');
};

$(document).ready(function() {
	$("#slider-holder ul").jcarousel({
		scroll: 1,
		auto: 6,
		wrap: 'both',
		initCallback: _init_slider,
		itemFirstInCallback: _active_slide,
		buttonNextHTML: null,
		buttonPrevHTML: null
	});
	
	$(".product-info").hover(
	  function () {
	    $(this).stop().animate({top: '0'}, 500);
	    } ,
	  function () {
	    $(this).stop().animate({top: '194'}, 500);
	  }
	);
	

	$('.fancybox').fancybox();

});
