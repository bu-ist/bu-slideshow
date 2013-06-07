/* modernizr, just for rgba */
;window.Modernizr=function(a,b,c){function u(a){j.cssText=a}function v(a,b){return u(prefixes.join(a+";")+(b||""))}function w(a,b){return typeof a===b}function x(a,b){return!!~(""+a).indexOf(b)}function y(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:w(f,"function")?f.bind(d||b):f}return!1}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m={},n={},o={},p=[],q=p.slice,r,s={}.hasOwnProperty,t;!w(s,"undefined")&&!w(s.call,"undefined")?t=function(a,b){return s.call(a,b)}:t=function(a,b){return b in a&&w(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=q.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(q.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(q.call(arguments)))};return e}),m.rgba=function(){return u("background-color:rgba(150,255,150,.5)"),x(j.backgroundColor,"rgba")};for(var z in m)t(m,z)&&(r=z.toLowerCase(),e[r]=m[z](),p.push((e[r]?"":"no-")+r));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)t(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},u(""),i=k=null,e._version=d,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+p.join(" "):""),e}(this,this.document);

jQuery(document).ready(function($) {

	window.BuModal = function BuModal(args) {

		if ( !(this instanceof BuModal)) {
			return new BuModal(args);
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