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
		this.manageCustomField();
	};
	
	SlideshowSelector.prototype.init = function() {
		this.customField = this.ui.find('#bu_slideshow_custom_transition');
		this.transitionSelect = this.ui.find('#bu_slideshow_select_transition');
		
		this.addHandlers();
	};
	
	SlideshowSelector.prototype.addHandlers = function() {
		var that = this;
		
		that.transitionSelect.live('change', function() {
			that.manageCustomField();
		})
	};
	
	SlideshowSelector.prototype.manageCustomField = function() {
		if (this.transitionSelect.val() === 'custom') {
			this.customField.show();
		} else {
			this.customField.hide();
		}
	};
	
	SlideshowSelector.prototype.getOptions = function() {
		var options = {};

		options.show_id = this.ui.find('#bu_slideshow_selected').val();
		options.show_nav = this.ui.find('#bu_slideshow_show_nav').is(':checked') ? 1 : 0;
		options.transition = this.ui.find('#bu_slideshow_select_transition').val();
		options.custom_transition = this.ui.find('#bu_slideshow_custom_transition').val().replace(' ', '');
		options.nav_style = this.ui.find('#bu_slideshow_nav_style').val();
		options.autoplay = this.ui.find('#bu_slideshow_autoplay').is(':checked') ? 1 : 0;

		if (options.transition === 'custom') {
			options.transition = options.custom_transition;
			var rplcd = {
				'[' : '',
				']' : '',
				'"' : ''
			};
			for (var r in rplcd) {
				options.transition = options.transition.replace(r, rplcd.r);
			}
		}

		return options;
	};
	
	SlideshowSelector.prototype.reset = function() {
		var slideSel, transSel, navSel, customTrans;

		slideSel = this.ui.find('#bu_slideshow_selected');
		slideSel.val(slideSel.find('option:first').val());
		transSel = this.ui.find('#bu_slideshow_select_transition');
		transSel.val(transSel.find('option:first').val());
		navSel = this.ui.find('#bu_slideshow_nav_style');
		navSel.val(navSel.find('option:first').val());
		
		this.ui.find('#bu_slideshow_custom_transition').hide();

		this.ui.find('#bu_slideshow_show_nav').attr('checked', 'checked');
		this.ui.find('#bu_slideshow_custom_transition').val('');
		this.ui.find('#bu_slideshow_autoplay').attr('checked', 'checked');
	};
	
});