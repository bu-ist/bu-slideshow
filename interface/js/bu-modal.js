jQuery(document).ready(function($) {
	
	window.buModal = function(args) {
		var that = {};
		
		if (!args.el) {
			return TypeError('Could not find modal element.');
		}
		var $el = $(args.el);
		$el.wrap('<div class="bu_modal" style="display:none;"></div>');
		$el.before('<div class="postboxheader"><a class="close_btn" href="">X</a></div>');
		
		that.ui = $el.parent('.bu_modal');
		that.closeButton = that.ui.find('.close_btn');
		that.ui.bg = $('<div class="bu_modal_bg"></div>').insertBefore(that.ui).hide();
		that.ui.hide();
		
		that.beforeOpen = args['beforeOpen'] ? args['beforeOpen'] : function() {};
		that.afterOpen = args['afterOpen'] ? args['afterOpen'] : function() {};
		that.beforeClose = args['beforeClose'] ? args['beforeClose'] : function() {};
		that.afterClose = args['afterClose'] ? args['afterClose'] : function() {};
		
		that.isOpen = false;
		
		that.bindHandlers = function() {
			// ESC to close
			$(document).keyup(function(e) {
				if (that.isOpen && e.which === 27) {
					that.close();
				}
			});
			
			// click overlay to close
			that.ui.bg.click(function() {
				that.close();
				return false;
			});
			
			that.closeButton.click(function() {
				that.close();
				return false;
			});
		};
		
		that.open = function() {
			var w, h, halfW, halfH;
			
			that.beforeOpen();
			
			that.ui.bg.show();
			that.ui.addClass('bu_modal').show();
			
			w = that.ui.outerWidth();
			h = that.ui.outerHeight();
			
			halfW = parseInt(w / 2);
			halfH = parseInt(h / 2);
			
			that.ui.css({
				'marginLeft' : '-' + halfW + 'px',
				'marginTop' : '-' + halfH + 'px'
			});
			that.isOpen = true;
			
			that.afterOpen();
		}
		
		that.close = function() {
			that.beforeClose();
			
			that.ui.removeClass('bu_modal').hide();
			that.ui.bg.hide();
			that.isOpen = false;
			
			that.afterClose();
		}
		
		that.bindHandlers();
		
		return that;
	}
	
});