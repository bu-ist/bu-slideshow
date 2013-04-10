jQuery(document).ready(function($) {
	window.buSlideshows = {};
	var container, pagerId, options, args, rotator, imgHeight;
	
	window.BuSlideshow = function BuSlideshow(args) {
		
		if ( !(this instanceof BuSlideshow)) {
			throw new ReferenceError('Invoked constructor as regular function. Use the "new" operator.');
		}
		
		if (!args.show) {
			throw new TypeError('Did not pass a valid Sequence object.');
		}
		
		if (!args.container) {
			throw new ReferenceError('Did not pass a valid container element.');
		}
		
		this.sequence = args.show;
		this.container = args.container;
		
		
		this.init(args);
	};
	
	BuSlideshow.prototype.init = function(args) {
		var that = this;
		
		if (!args) {
			args = {};
		}
		
		this.sequence.afterLoaded = bu_resize_slideshow;
		
		this.sequence.beforeNextFrameAnimatesIn = function() {
			if (that.pager) {
				that.pager.setActive();
			}
		}

		this.pager = $('#' + args.pager).length ? $('#' + args.pager) : false;
		if (this.pager) {
			this.initPager();
		}

		this.arrows = $('#' + args.arrows).length ? $('#' + args.arrows) : false;
		if (this.arrows) {
			this.initArrows();
		}
	};
	
	BuSlideshow.prototype.initPager = function() {
		var that = this;

		this.pager.find('li a').bind('click', function() {
			var id = $(this).attr('id').replace('pager-', '');
			that.sequence.nextFrameID = id;
			that.sequence.goTo(id);
			return false;
		});

		this.pager.setActive = function(nextId) {
			nextId = that.sequence.nextFrameID;	
			this.find('a').removeClass('active');
			this.find('a#pager-' + nextId).addClass('active');
		}
	};
	
	BuSlideshow.prototype.initArrows = function() {
		var that = this;
		
		this.arrows.find('.bu-slideshow-arrow-left').bind('click', function() {
			that.sequence.prev();
			return false;
		}).end().find('.bu-slideshow-arrow-right').bind('click', function() {
			that.sequence.next();
			return false;
		});
	};
	

	$('.bu-slideshow-container').each(function(index, el){
		var $this = $(this), autoplay = false, container, pagerId, arrowId, 
			options, args, name;
		
		container = $this.find('.bu-slideshow-slides');
		pagerId = $this.find('ul.bu-slideshow-navigation').attr('id');
		arrowId = $this.find('div.bu-slideshow-arrows').attr('id');
		
		name = $this.attr('data-slideshow-name') ? $this.attr('data-slideshow-name') : index;
		
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
			'container' : container,
			'pager' : pagerId,
			'arrows' : arrowId
		};
		
		buSlideshows[name] = new BuSlideshow(args);
	});

	/**
	 * Resizes slideshow and all slides to height of highest slide
	 */
	function bu_resize_slideshow() {
		$('.bu-slideshow-container').each(function(){
			var container = $(this), slides = container.find('li .bu-slide-container'), $el, height = 0, currentHeight = 0;

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