jQuery(document).ready(function($){
	
	window.SlideshowSelector = function SlideshowSelector(el) {
		
		if ( !(this instanceof SlideshowSelector)) {
			return new SlideshowSelector(el);
		}
		
		this.ui = $(el);
		if (!this.ui.length) {
			return new TypeError('No selector element found.');
		}
		
		this.init();
	};
	
	SlideshowSelector.prototype.init = function() {
		this.advancedToggle = this.ui.find('.bu-slideshow-advanced-toggle');
		this.advanced = this.ui.find('.bu-slideshow-selector-advanced');
		
		this.advanced.hide();
		this.addHandlers();
	};
	
	SlideshowSelector.prototype.addHandlers = function() {
		var that = this;
		
		that.ui.on('click', '.bu-slideshow-advanced-toggle', function(e) {
			if (that.advanced.is(':hidden')) {
				that.advanced.slideDown(200);
				that.advancedToggle.text(buSlideshowLocalSelector.toggleTextHide);
				$('.bu-slideshow-selector-advanced input').first().focus();
			} else {
				that.advanced.slideUp(200);
				that.advancedToggle.text(buSlideshowLocalSelector.toggleTextShow);
			}

			return false;
		});
	};
	
	SlideshowSelector.prototype.getOptions = function() {
		var options = {};

		options.show_id = this.ui.find('#bu_slideshow_selected').val();
		options.show_nav = this.ui.find('#bu_slideshow_show_nav').is(':checked') ? 1 : 0;
		options.transition = this.ui.find('#bu_slideshow_select_transition').val();
		options.custom_transition = this.ui.find('#bu_slideshow_custom_trans').val().replace(' ', '');
		options.nav_style = this.ui.find('#bu_slideshow_nav_style').val();
		options.autoplay = this.ui.find('#bu_slideshow_autoplay').is(':checked') ? 1 : 0;
		options.transition_delay = this.ui.find('#bu_slideshow_transition_delay').val();
		options.width = this.ui.find('#bu_slideshow_width').val();
		// options.align = this.ui.find('#bu_slideshow_alignment input[type="radio"]:checked').val();
		// options.align = this.ui.each('.bu_slideshow_alignment_loop input[name="bu_slideshow_alignment"]:checked').val());

		// override transition with custom transition if provided
		if (options.custom_transition.length > 0) {
			options.transition = options.custom_transition;
			var rplcd = {
				'[' : '',
				']' : '',
				'"' : ''
			};
			for (var r in rplcd) {
				options.transition = options.transition.replace(r, rplcd[r]);
			}
		}
		
		if (options.width.length === 0) {
			options.width = 'auto';
		}
		
		// if (options.align.legnth || options.align !== 'undefined' || options.align.length === 0) {
		// options.align = 'center';
		// }

		return options;
	};
	
	SlideshowSelector.prototype.reset = function() {
		var slideSel, transSel, navSel;

		slideSel = this.ui.find('#bu_slideshow_selected');
		slideSel.val(slideSel.find('option:first').val());
		transSel = this.ui.find('#bu_slideshow_select_transition');
		transSel.val(transSel.find('option:first').val());
		navSel = this.ui.find('#bu_slideshow_nav_style');
		navSel.val(navSel.find('option:first').val());
		
		this.ui.find('#bu_slideshow_width').val('');
		// this.ui.find('#bu_slideshow_alignment input[type="radio"]:checked').val('');
		this.ui.find('#bu_slideshow_custom_trans').val('');
		this.ui.find('#bu_slideshow_custom_trans').val('');

		this.ui.find('#bu_slideshow_show_nav').prop('checked', true);
		this.ui.find('#bu_slideshow_custom_transition').val('');
		this.ui.find('#bu_slideshow_autoplay').prop('checked', true);
	};
	
});