jQuery( document ).ready(function($){
	/* IE triggers resize all over the place, so we check actual window dimensions */
	var windowHeight = jQuery(window).height(),
		windowWidth = jQuery(window).width(),
		buSlideshows = {},
		rotator, imgHeight;

		/**
		 * Resizes slideshow and all slides to height of highest slide
		 *
		 * I hate iterating through everything in the slides here, but we should allow
		 * for markup other than what the plugin currently produces (e.g. video, custom HTML).
		 */
		 /*Updated to allow for captions below slides*/

		function bu_resize_slideshow() {
			$('.bu-slideshow-navigation').css( 'height', 0 );
			$('.bu-slideshow-container').each(function(){
				var slides = $(this).find('li .bu-slide-container'),
					$el;
				/*We need to get the height of each element to postiion the navigation and any caption-under-slide captions individually*/
				var totalHeight = 0;
				var capHeight = 0;
				var currentCapHeight = 0;
				var imageHeight = 0;
				var currentImageHeight = 0;
				var imageHeight = 0;

				//loop through each element of the slide
				slides.find('*').each(function(i, el) {
					$el = $(el);

					/*if there is a caption below any slide we need to ge the tallest image to position the navigation, the caption, and calculate the total slideshow height*/
					if ( $el.find("img") && $el.attr('src') ){
						currentImageHeight = $el.height();
						if (currentImageHeight > imageHeight) {
							imageHeight = currentImageHeight;
						}
					}
					/*we need the height of the tallest caption below slide to calculate the total slideshow height and a consistent size for those captions*/
					if ( $el.hasClass('caption-under-slide') ) {
						/*the padding of the height property needs to be ironed out
						getting the height of div.caption-under-slide gets unexpected results. adding the P1 and p2 - slide title and text - gives consistent results but comes up short*/
						currentCapHeight = $( "div.caption-under-slide p:nth-child(1)" ).height();
						currentCapHeight += $( "div.caption-under-slide p:nth-child(2)" ).height();
						currentCapHeight += (currentCapHeight)/**1.08*/;

						if (currentCapHeight > capHeight) {
							capHeight = currentCapHeight;
						}

					}

				});


				/*Set each under slide caption to the same height for consistency*/
				$('DIV.caption-under-slide').css( 'height', capHeight);
				/*Set position for each under slide caption.
				The top of the caption will float at the bottom edge of the tallest image regardless of which image the caption is under*/
				$('DIV.caption-under-slide').css( 'top', imageHeight);
				/*get the total height and set each slide*/
				totalHeight = ( imageHeight + capHeight);
				$("DIV.bu-slideshow-container").css( 'height', totalHeight);
				/*position navigation*/
				$('.bu-slideshow-navigation').css( 'top', ( imageHeight * .9 ) );


			});

		}

		function BuSlideshow(args) {

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
		}

		window.BuSlideshow = BuSlideshow;

		BuSlideshow.prototype.init = function(args) {
			var that = this;

			if (!args) {
				args = {};
			}


			this.sequence.afterLoaded = function(){
				var outer = that.container.parent('div.bu-slideshow-container');
				outer.find('.slideshow-loader.active').removeClass('active');
				outer.find('.bu-slideshow-navigation-container').css('display', 'inline-block');
				bu_resize_slideshow();
			};

			this.sequence.beforeNextFrameAnimatesIn = function() {
				if (that.pager) {
					that.pager.setActive();
				}
			};

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
			};
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

	jQuery(document).ready(function($) {

		$('.bu-slideshow-container').each(function(index, el){
			var $this = $(this), autoplay = false, container, pagerId, arrowId,
				options, args, name, transition_delay;

			container = $this.find('.bu-slideshow-slides');
			pagerId = $this.find('ul.bu-slideshow-navigation').attr('id');
			arrowId = $this.find('div.bu-slideshow-arrows').attr('id');

			name = $this.attr('data-slideshow-name') ? $this.attr('data-slideshow-name') : index;
			transition_delay = $this.attr('data-slideshow-delay') ? $this.attr('data-slideshow-delay') : 5000;

			if ($this.hasClass('autoplay')) {
				autoplay = true;
			}

			options = {
				autoPlay: autoplay,
				autoPlayDelay: transition_delay,
				fallback: {
					theme : 'slide'
				},
				swipeEvents: {
					left: "next",
					right: "prev"
				}
			};
			args = {
				'show' : container.sequence(options).data('sequence'),
				'container' : container,
				'pager' : pagerId,
				'arrows' : arrowId
			};

			try {
				buSlideshows[name] = new BuSlideshow(args);
			}
			catch (e){
			}
		});


		/**
		 * Dear IE: is this really a resize event?
		 */
		$(window).resize(function() {

			var currentHeight, currentWidth;

			currentHeight = $(window).height();
			currentWidth = $(window).width();

			if (currentHeight !== windowHeight || currentWidth !== windowWidth) {

				windowHeight = currentHeight;
				windowWidth = currentWidth;
				bu_resize_slideshow();

			}

		});

	});
}(jQuery));