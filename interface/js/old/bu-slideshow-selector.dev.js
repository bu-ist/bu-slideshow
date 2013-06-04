jQuery(document).ready(function($){
	
	window.SlideshowSelector = function SlideshowSelector(el) {
		
		if ( !(this instanceof SlideshowSelector)) {
			return new ReferenceError('Contstructor invoked as regular function. Use "new" operator.');
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
		
		that.ui.delegate('.bu-slideshow-advanced-toggle', 'click', function(e) {
			if (that.advanced.is(':hidden')) {
				that.advanced.slideDown(200);
				that.advancedToggle.text(buSlideshowLocalSelector.toggleTextHide);
			} else {
				that.advanced.slideUp(200);
				that.advancedToggle.text(buSlideshowLocalSelector.toggleTextShow);
			}
			
			e.preventDefault();
			e.stopPropagation();
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

		this.ui.find('#bu_slideshow_show_nav').attr('checked', 'checked');
		this.ui.find('#bu_slideshow_autoplay').attr('checked', 'checked');
	};
	
});