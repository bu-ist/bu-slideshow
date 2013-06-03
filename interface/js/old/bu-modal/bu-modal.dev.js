jQuery(document).ready(function($) {
	
	// don't redefine
	if (typeof BuModal === 'function') {
		return;
	}
	
	window.BuModal = function BuModal(args) {
		
		if ( !(this instanceof BuModal)) {
			throw new ReferenceError('Constructor was called as ordinary function. Use "new" operator.');
		}
		
		if (!args.el) {
			throw new TypeError('Could not identify modal element.');
		}
		
		var $el = $(args.el);
		$el.wrap('<div class="bu_modal" style="display:none;"></div>');
		$el.before('<div class="postboxheader"><a class="close_btn" href="">X</a></div>');
		
		this.ui = $el.parent('.bu_modal');
		
		this.beforeOpen = args['beforeOpen'] ? args['beforeOpen'] : function() {};
		this.afterOpen = args['afterOpen'] ? args['afterOpen'] : function() {};
		this.beforeClose = args['beforeClose'] ? args['beforeClose'] : function() {};
		this.afterClose = args['afterClose'] ? args['afterClose'] : function() {};
		
		this.init();
		this.bindHandlers();
	};
	
	BuModal.prototype.init = function() {
		this.closeButton = this.ui.find('.close_btn');
		this.ui.bg = $('<div class="bu_modal_bg"></div>').insertBefore(this.ui).hide();
		this.ui.hide();
	};
	
	BuModal.prototype.isOpen = false;
	
	BuModal.prototype.bindHandlers = function() {
		
		var that = this;
		
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
	
	BuModal.prototype.open = function() {
		var w, h, halfW, halfH;

		this.beforeOpen();

		this.ui.bg.show();
		this.ui.addClass('bu_modal').show();

		w = this.ui.outerWidth();
		h = this.ui.outerHeight();

		halfW = parseInt(w / 2);
		halfH = parseInt(h / 2);

		this.ui.css({
			'marginLeft' : '-' + halfW + 'px'
		});
		this.isOpen = true;

		this.afterOpen();
	};
	
	BuModal.prototype.close = function() {
		this.beforeClose();

		this.ui.removeClass('bu_modal').hide();
		this.ui.bg.hide();
		this.isOpen = false;

		this.afterClose();
	};
	
});