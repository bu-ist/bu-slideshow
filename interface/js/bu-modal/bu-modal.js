/* modernizr, just for rgba */
;window.Modernizr=function(a,b,c){function u(a){j.cssText=a}function v(a,b){return u(prefixes.join(a+";")+(b||""))}function w(a,b){return typeof a===b}function x(a,b){return!!~(""+a).indexOf(b)}function y(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:w(f,"function")?f.on(d||b):f}return!1}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m={},n={},o={},p=[],q=p.slice,r,s={}.hasOwnProperty,t;!w(s,"undefined")&&!w(s.call,"undefined")?t=function(a,b){return s.call(a,b)}:t=function(a,b){return b in a&&w(a.constructor.prototype[b],"undefined")},Function.prototype.on||(Function.prototype.on=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=q.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(q.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(q.call(arguments)))};return e}),m.rgba=function(){return u("background-color:rgba(150,255,150,.5)"),x(j.backgroundColor,"rgba")};for(var z in m)t(m,z)&&(r=z.toLowerCase(),e[r]=m[z](),p.push((e[r]?"":"no-")+r));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)t(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},u(""),i=k=null,e._version=d,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+p.join(" "):""),e}(this,this.document);

jQuery(document).ready(function($) {
	
	window.BuModal = function BuModal(args) {
		
		if ( !(this instanceof BuModal)) {
			return new BuModal(args);
		}
		
		this.beforeOpen = args['beforeOpen'] ? args['beforeOpen'] : function() {};
		this.afterOpen = args['afterOpen'] ? args['afterOpen'] : function() {};
		this.beforeLoad = args['beforeLoad'] ? args['beforeLoad'] : function() {};
		this.afterLoad = args['afterLoad'] ? args['afterLoad'] : function() {};
		this.beforeClose = args['beforeClose'] ? args['beforeClose'] : function() {};
		this.afterClose = args['afterClose'] ? args['afterClose'] : function() {};
		this.buttons = args['buttons'] ? $(args['buttons']) : $();
		this.background = args['background'] ? args['background'] : '#ffffff';
		this.el = args['el'] ? $(args['el']) : $('<div>').appendTo(document.body);
		this.content_url = args['content_url'] ? args['content_url'] : '';
		this.width = args['width'] ? args['width'] : 'fit-content';
		this.height = args['height'] ? args['height'] : 'fit-content';
		
		// An element can have multiple modals bound to it, we re-use the bu_modal container.
		this.ui = this.el.parents('.bu_modal');
		if (!this.ui.length) {
			this.el.wrap('<div class="bu_modal" style="display:none;"></div>');
			this.el.before('<div class="postboxheader"><a class="close_btn" href="">X</a></div>');
			this.ui = this.el.parents('.bu_modal');
		}
		
		if (this.background) {
			this.ui.css('background', this.background);
		}

		this.init();
		this.onHandlers();
	};

	BuModal.version = '2.0.6';

	BuModal.bg = $('<div class="bu_modal_bg"></div>').prependTo(document.getElementsByTagName('body')[0]).hide();	
	BuModal.active_modal = false;
	
	BuModal.close = function() {
		if (BuModal.active_modal) {
			BuModal.active_modal.close();
		}
	};
	
	BuModal.prototype.init = function() {
		var modal = this;
		modal.closeButton = this.ui.find('.close_btn');
		modal.ui.bg = BuModal.bg;
		modal.ui.hide();
		modal.buttons.each(function() {
			$(this).click(function() {
				modal.open();
			});
		});
	};
	
	BuModal.prototype.isOpen = false;
	
	BuModal.prototype.onHandlers = function() {
			
		var that = this;

		// ESC to close
		$(document).on('keyup', function(e) {
			if (that.isOpen && e.which === 27) {
				that.close();
			}
		});

		// click overlay to close
		that.ui.bg.on('click', function() {
			that.close();
			return false;
		});

		that.closeButton.on('click', function() {
			that.close();
			return false;
		});
	};
	
	BuModal.prototype.open = function() {
		var w, h, halfW, halfH, modal = this;

		this.ui.css({
			'width': this.width,
			'height': this.height
		});

		this.beforeOpen();

		this.el.show();
		this.ui.bg.show();
		this.ui.addClass('active').show();

		w = this.ui.outerWidth();
		h = this.ui.outerHeight();

		halfW = parseInt(w / 2);
		halfH = parseInt(h / 2);

		this.ui.css({
			'marginLeft' : '-' + halfW + 'px',
			'marginRight': halfW + 'px'
		});
		this.isOpen = true;
		BuModal.active_modal = this;

		this.afterOpen();

		if (this.content_url) {
			this.beforeLoad();
			this.ui.addClass('loading_content');
			this.xhr = $.get(this.content_url, function(response) {
				var content_type = modal.xhr.getResponseHeader('Content-Type');
				modal.xhr = false;

				if (content_type.split(';')[0] == 'text/html') {
					modal.el.html(response);
				} else {
					modal.el.text(response);
				}
				
				modal.ui.removeClass('loading_content');
				modal.afterLoad();
			});
		}
	};
	
	BuModal.prototype.close = function() {
		this.beforeClose();

		if (this.xhr) {
			this.xhr.abort();
			this.xhr = false;
		}
		this.ui.removeClass('active').hide();
		this.ui.bg.hide();
		this.isOpen = false;
		BuModal.active_modal = false;

		this.afterClose();
	};
	
});
