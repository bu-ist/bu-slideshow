jQuery(document).ready(function($) {

	$('.bu-slideshow-container').each(function(){
		var container = $(this);
		var rotator = container.find('ol.bu-slideshow');
		var pagerId = container.find('ol.bu-slideshow-navigation').attr('id');
		
		rotator.cycle({
			pager: '#' + pagerId,
			pause: 1,
			containerResize: false,
			slideResize: false,
			fit: 1,
			timeout: 6000,
			pagerAnchorBuilder: function(idx, slide) { 
				return '#' + pagerId + ' li:eq(' + idx + ') a'; 
			}
		});
		
		/* prevents edge cases where slideshow nav appears too high  */
		setTimeout(resize_slideshow, 100);
	})

	function resize_slideshow() {
		$('.bu-slideshow-container').each(function(){
			var container = $(this);
			var rotator = container.find('ol.bu-slideshow');
			
			var imgHeight = rotator.find('li img:visible').height();
			
			/** todo abstract this imgHeight max to an option that can be set */
			if (imgHeight > 500) {
				imgHeight = 500;
			}
			
			container.css('height', imgHeight + 'px');
			rotator.css('height', imgHeight + 'px');
		});
		
	}
	
	$(window).resize(function() {
		resize_slideshow();
	});
	
	resize_slideshow();
	
});