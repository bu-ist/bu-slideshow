jQuery(document).ready(function($) {
	window.buSlideshows = {};
	var container, pagerId, options, args, rotator, imgHeight;
	
	function buSlideshow(args) {
		var self = {};
		
		if (!args.show) {
			throw new TypeError('Did not pass a valid Sequence object.');
		}
		
		self.sequence = args.show;
		
		self.init = function() {
			self.sequence.afterLoaded = resize_slideshow;
			self.sequence.beforeNextFrameAnimatesIn = function() {
				if (!self.hasPager) {
					return;
				}
				
				self.pager.setActive();
			}
		}
		
		
		self.hasPager = args.pager ? true : false;
		if (self.hasPager) {
			self.pager = $('#' + args.pager);
		
			self.pager.find('li a').bind('click', function() {
				var id = $(this).attr('id').replace('pager-', '');
				self.sequence.goTo(id);
				return false;
			});

			self.pager.setActive = function() {
				var currentId = self.sequence.currentFrameID;
				self.pager.find('a').removeClass('active');
				self.pager.find('a#pager-' + currentId).addClass('active');
			}
		}
		
		self.init();
		
		return self;
	}

	$('.bu-slideshow-container').each(function(index, el){
		var $this = $(this);
		container = $this.find('.bu-slideshow-slides');
		pagerId = $this.find('ul.bu-slideshow-navigation').attr('id');
		
		options = {
			autoPlay: true,
			autoPlayDirection: -1,
			fallback: {
				theme : 'slide'
			}
		};
		args = {
			'show' : container.sequence(options).data('sequence'),
			'pager' : pagerId
		};
		buSlideshows[index] = buSlideshow(args);
		
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