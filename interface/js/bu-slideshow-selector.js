jQuery(document).ready(function($){
	
	window.slideshowSelector = function(el) {
		var that = {};
		
		that.ui = $(el);
		if (!that.ui.length) {
			return TypeError('No selector element found.');
		}
		
		that.customField = that.ui.find('#bu_slideshow_custom_transition');
		that.transitionSelect = that.ui.find('#bu_slideshow_select_transition');
		that.manageCustomField = function() {
			if (that.transitionSelect.val() === 'custom') {
				that.customField.show();
			} else {
				that.customField.hide();
			}
		}
		
		that.transitionSelect.on('change', function() {
			that.manageCustomField();
		})
		
		that.manageCustomField();
		
		that.getOptions = function() {
			var options = {};
			
			options.show_id = that.ui.find('#bu_slideshow_selected').val();
			options.show_nav = that.ui.find('#bu_slideshow_show_nav').is(':checked') ? 1 : 0;
			options.transition = that.ui.find('#bu_slideshow_select_transition').val();
			options.custom_transition = that.ui.find('#bu_slideshow_custom_transition').val().replace(' ', '');
			options.nav_style = that.ui.find('#bu_slideshow_nav_style').val();
			options.autoplay = that.ui.find('#bu_slideshow_autoplay').is(':checked') ? 1 : 0;
			
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
		}
		
		that.reset = function() {
			var slideSel, transSel, navSel;
			
			slideSel = that.ui.find('#bu_slideshow_selected');
			slideSel.val(slideSel.find('option:first').val());
			
			transSel = that.ui.find('#bu_slideshow_select_transition');
			transSel.val(transSel.find('option:first').val());
			
			navSel = that.ui.find('#bu_slideshow_nav_style');
			navSel.val(navSel.find('option:first').val());
			
			that.ui.find('#bu_slideshow_show_nav').prop('checked', true);
			that.ui.find('#bu_slideshow_custom_transition').val('');
			that.ui.find('#bu_slideshow_autoplay').prop('checked', true);
		}
		
		return that;
	}
	
});