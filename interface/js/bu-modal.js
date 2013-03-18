jQuery(document).ready(function($) {
	
	window.buModal = function(el) {
		var that = {}, ui;
		
		that.ui = $(el);
		if (!that.ui.length) {
			return TypeError('Could not find modal element.');
		}
		that.ui.hide();
		
		that.ui.bg = $('<div class="bu_modal_bg"></div>').insertBefore(that.ui).hide();
		// click overlay to close
		that.ui.bg.click(function() {
			that.close();
		});
		
		// ESC to close
		$(document).keypress(function(e) {
			if (that.isOpen) {
				if (e.which === 0) {
					that.close();
				}
			}
		})
		
		that.isOpen = false;
		
		that.closeButton = that.ui.find('a.close_btn');
		that.closeButton.click(function() {
			that.close();
			return false;
		})
		
		that.open = function() {
			that.ui.bg.show();
			that.ui.addClass('bu_modal').show();
			var w = that.ui.outerWidth(), h = that.ui.outerHeight(), halfW, halfH;
			halfW = parseInt(w / 2);
			halfH = parseInt(h / 2);
			that.ui.css({
				'marginLeft' : '-' + halfW + 'px',
				'marginTop' : '-' + halfH + 'px'
			});
			that.isOpen = true;
		}
		
		that.close = function() {
			that.ui.removeClass('bu_modal').hide();
			that.ui.bg.hide();
			that.isOpen = false;
		}
		
		return that;
	}
	
});