jQuery(document).ready(function($) {

	$('.bu-slideshow-container').each(function(){
		var container = $(this);
		var rotator = container.find('ul.bu-slideshow');
		var pagerId = container.find('ul.bu-slideshow-navigation').attr('id');
		
		var options = {
			autoplay: true
		};
		var sequence = container.sequence(options).data('sequence');
		sequence.afterLoaded = function() {
			resize_slideshow();
		}
		
		/* prevents edge cases where slideshow nav appears too high  */
		//setTimeout(resize_slideshow, 1000);
	})

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