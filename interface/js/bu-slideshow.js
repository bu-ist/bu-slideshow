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
			
			self.hasPager = args.pager ? true : false;
			if (self.hasPager) {
				self.initPager();
			}
			
			self.hasArrows = args.arrows ? true : false;
			if (self.hasArrows) {
				self.initArrows();
			}
			
		}
		
		self.initPager = function() {
			self.pager = $('#' + args.pager);

			self.pager.find('li a').bind('click', function() {
				var id = $(this).attr('id').replace('pager-', '');
				self.sequence.nextFrameID = id;
				self.sequence.goTo(id);
				return false;
			});

			self.pager.setActive = function(nextId) {
				nextId = self.sequence.nextFrameID;	
				self.pager.find('a').removeClass('active');
				self.pager.find('a#pager-' + nextId).addClass('active');
			}
		};
		
		self.initArrows = function() {
			self.arrows = $('#' + args.arrows);
			
			self.arrows.find('.bu-slideshow-arrow-left').bind('click', function() {
				self.sequence.prev();
				return false;
			}).end().find('.bu-slideshow-arrow-right').bind('click', function() {
				self.sequence.next();
				return false;
			});
		};
		
		self.init();
		
		return self;
	}

	$('.bu-slideshow-container').each(function(index, el){
		var $this = $(this), autoplay = false, container, pagerId, arrowId, options, args;
		container = $this.find('.bu-slideshow-slides');
		pagerId = $this.find('ul.bu-slideshow-navigation').attr('id');
		arrowId = $this.find('div.bu-slideshow-arrows').attr('id');
		
		if ($this.hasClass('autoplay')) {
			autoplay = true;
		}
		
		options = {
			autoPlay: autoplay,
			fallback: {
				theme : 'slide'
			}
		};
		args = {
			'show' : container.sequence(options).data('sequence'),
			'pager' : pagerId,
			'arrows' : arrowId
		};
		buSlideshows[index] = buSlideshow(args);
		
	})

	/**
	 * Resizes slideshow and all slides to height of highest slide
	 */
	function resize_slideshow() {
		$('.bu-slideshow-container').each(function(){
			var container = $(this), slides, numSlides, height = 0, currentHeight = 0;
			
			slides = container.find('li .bu-slide-container'),
			numSlides = slides.length,
			
			slides.each(function(i, el) {
				currentHeight = $(el).height();
				if (currentHeight > height) {
					height = currentHeight;
				}
			});
			
			/** todo abstract this height max to an option that can be set */
			if (height > 500) {
				height = 500;
			}
			
			slides.each(function(i, el) {
				$(el).css('height', height + 'px');
			});
			
			container.css('height', height + 'px');
			container.find('ul.bu-slideshow').css('height', height + 'px');
		});
		
	}
	
	$(window).resize(function() {
		resize_slideshow();
	});
	
});