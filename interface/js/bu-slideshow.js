jQuery( document ).ready(function($){
	/* IE triggers resize all over the place, so we check actual window dimensions */
	var windowHeight = jQuery(window).height(),
		windowWidth = jQuery(window).width(),
		buSlideshows = {},
		rotator, imgHeight;
		/*alert( $('.bu-slideshow-container').height() + ' ' + $('li .bu-slide-container').height() + ' ' + $( "div.caption-under-slide" ).height());
*/
		/**
		 * Resizes slideshow and all slides to height of highest slide
		 *
		 * I hate iterating through everything in the slides here, but we should allow
		 * for markup other than what the plugin currently produces (e.g. video, custom HTML).
		 */

		function bu_resize_slideshow() {
			$('.bu-slideshow-navigation').css( 'height', 0 );
			$('.bu-slideshow-container').each(function(){
				var slides = $(this).find('li .bu-slide-container'),
					$el, height = 0, currentHeight = 0;
				/*We need to get the height of each element to postiion the navigation and the caption individually*/
				var checkheight = 0;
				var capHeight = 0;
				var currentCapHeight = 0;
				var imageHeight = 0;//
				var currentImageHeight;
				var imageHeight = 0;

				//loop through each element of the slide
				slides.find('*').each(function(i, el) {
					$el = $(el);
					currentImageHeight = 0;//
					currentCapHeight = 0;//
					var checkheight = 0;


					/*when the caption is below the slide we need to ge the largest caption height to position the navigation and make room for the caption*/
					if ( $el.hasClass('caption-under-slide') ) {
						currentCapHeight = $( "div.caption-under-slide p:nth-child(2)" ).height() + 40;
						currentCapHeight += $( "div.caption-under-slide p:nth-child(1)" ).height() * 1.5;
						//alert(currentCapHeight + ' ' + capHeight);
						if (currentCapHeight > capHeight) {
							capHeight = currentCapHeight;
						}

					}

					if ( $el.find("img") && $el.attr('src') ){

							currentImageHeight = $el.height();
							if (currentImageHeight > imageHeight) {
								imageHeight = currentImageHeight;
							}

						}
					});

				slides.find('*').each(function(i, el) {
					$el = $(el);
					if ( $el.hasClass('caption-under-slide') ) {
						$(this).height(capHeight);
					}


					});

				/*alert(height + ' ' + imageHeight + ' ' + capHeight + ' ' + textHeight  + ' CTH: ' + currentTextHeight);*/
				checkheight = ( imageHeight + capHeight);

$("DIV.bu-slideshow-container").each(function(index, value) {
					    $(value).height(checkheight);
					});

				//$('DIV.bu-slide-caption').css( 'height', 'auto');
				$('.caption-under-slide').css( 'top', imageHeight);
				//$('.caption-under-slide').css( 'height', capHeight);
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