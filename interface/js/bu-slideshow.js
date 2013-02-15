jQuery(document).ready(function($) {

	$('.bu-slideshow-container').each(function(){
		var container = $(this);
		var pagerId = container.find('ul.bu-slideshow-navigation').attr('id');
		
		var options = {
			autoPlay: true,
			autoPlayDirection: -1,
			fallback: {
				theme : 'slide'
			}
		};
		var sequence = container.sequence(options).data('sequence');
		sequence.afterLoaded = function() {
			resize_slideshow();
		}

	})

	/**
	 * @todo more flexible detection of content, don't rely on images to set height
	 */
	function resize_slideshow() {
		$('.bu-slideshow-container').each(function(){
			var container = $(this);
			var rotator = container.find('ul.bu-slideshow');
			
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