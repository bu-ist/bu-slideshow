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
			self.sequence.afterLoaded = bu_resize_slideshow;
			self.sequence.beforeNextFrameAnimatesIn = function() {
				if (self.pager) {
					self.pager.setActive();
				}
			}
			
			self.pager = $('#' + args.pager).length ? $('#' + args.pager) : false;
			
			if (self.pager) {
				self.initPager();
			}
			
			self.arrows = $('#' + args.arrows).length ? $('#' + args.arrows) : false;
			
			if (self.arrows) {
				self.initArrows();
			}
			
		}
		
		self.initPager = function() {

			self.pager.find('li a').bind('click', function() {
				var id = $(this).attr('id').replace('pager-', '');
				self.sequence.nextFrameID = id;
				self.sequence.goTo(id);
				return false;
			});

			self.pager.setActive = function(nextId) {
				nextId = self.sequence.nextFrameID;	
				this.find('a').removeClass('active');
				this.find('a#pager-' + nextId).addClass('active');
			}
		};
		
		self.initArrows = function() {
			
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
	function bu_resize_slideshow() {
		$('.bu-slideshow-container').each(function(){
			var container = $(this), slides = container.find('li .bu-slide-container'), $el, height = 0, currentHeight = 0;

			// this sucks but it handles consistent slide height and responsive height
			slides.each(function(i, el) {
				$el = $(el);
				$el.css('height', 'auto');
				currentHeight = $el.height();
				if (currentHeight > height) {
					height = currentHeight;
				}
			});
			
			slides.each(function(i, el) {
				$(el).css('height', height + 'px');
			})
			
			container.css('height', height + 'px');
			container.find('ul.bu-slideshow').css('height', height + 'px');
		});
		
	}
	
	$(window).resize(function() {
		bu_resize_slideshow();
	});
	
});