/*! COMPILED BY GRUNT. DO NOT MODIFY. bu-slideshow 20-08-2014 */
(function(e){function m(c,d,b,g){function h(){a.afterLoaded();a.settings.hideFramesUntilPreloaded&&a.settings.preloader&&a.sequence.children("li").show();a.settings.preloader?a.settings.hidePreloaderUsingCSS&&a.transitionsSupported?(a.prependPreloadingCompleteTo=!0==a.settings.prependPreloadingComplete?a.settings.preloader:e(a.settings.prependPreloadingComplete),a.prependPreloadingCompleteTo.addClass("preloading-complete"),setTimeout(k,a.settings.hidePreloaderDelay)):a.settings.preloader.fadeOut(a.settings.hidePreloaderDelay,
function(){clearInterval(a.defaultPreloader);k()}):k()}function f(a,b){function c(){var a=e(s),d=e(n);g&&(n.length?g.reject(l,a,d):g.resolve(l));e.isFunction(b)&&b.call(f,l,a,d)}function d(a,b){a.src!==BLANK&&-1===e.inArray(a,u)&&(u.push(a),b?n.push(a):s.push(a),e.data(a,"imagesLoaded",{isBroken:b,src:a.src}),h&&g.notifyWith(e(a),[b,l,e(s),e(n)]),l.length===u.length&&(setTimeout(c),l.unbind(".imagesLoaded")))}BLANK="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";var f=a,g=
e.isFunction(e.Deferred)?e.Deferred():0,h=e.isFunction(g.notify),l=f.find("img").add(f.filter("img")),u=[],s=[],n=[];e.isPlainObject(b)&&e.each(b,function(a,l){if("callback"===a)b=l;else if(g)g[a](l)});l.length?l.bind("load.imagesLoaded error.imagesLoaded",function(a){d(a.target,"error"===a.type)}).each(function(a,b){var l=b.src,c=e.data(b,"imagesLoaded");if(c&&c.src===l)d(b,c.isBroken);else if(b.complete&&void 0!==b.naturalWidth)d(b,0===b.naturalWidth||0===b.naturalHeight);else if(b.readyState||
b.complete)b.src=BLANK,b.src=l}):c()}function k(){e(a.settings.preloader).remove();a.nextButton=a.init.uiElements(a.settings.nextButton,".next");a.prevButton=a.init.uiElements(a.settings.prevButton,".prev");a.pauseButton=a.init.uiElements(a.settings.pauseButton,".pause");void 0!==a.nextButton&&!1!==a.nextButton&&a.settings.showNextButtonOnInit&&a.nextButton.show();void 0!==a.prevButton&&!1!==a.prevButton&&a.settings.showPrevButtonOnInit&&a.prevButton.show();void 0!==a.pauseButton&&!1!==a.pauseButton&&
a.pauseButton.show();!1!==a.settings.pauseIcon?(a.pauseIcon=a.init.uiElements(a.settings.pauseIcon,".pause-icon"),void 0!==a.pauseIcon&&a.pauseIcon.hide()):a.pauseIcon=void 0;a.nextFrameID=a.settings.startingFrameID;a.settings.hashTags&&(a.sequence.children("li").each(function(){a.frameHashID.push(e(this).attr(a.getHashTagFrom))}),a.currentHashTag=location.hash.replace("#",""),void 0===a.currentHashTag||""===a.currentHashTag?a.nextFrameID=a.settings.startingFrameID:(a.frameHashIndex=e.inArray(a.currentHashTag,
a.frameHashID),a.nextFrameID=-1!==a.frameHashIndex?a.frameHashIndex+1:a.settings.startingFrameID));a.nextFrame=a.sequence.children("li:nth-child("+a.nextFrameID+")");a.nextFrameChildren=a.nextFrame.children();a.sequence.css({width:"100%",height:"100%",position:"relative"});a.sequence.children("li").css({width:"100%",height:"100%",position:"absolute","z-index":1});a.transitionsSupported?a.settings.animateStartingFrameIn?a.settings.reverseAnimationsWhenNavigatingBackwards&&a.settings.autoPlayDirection-
1&&a.settings.animateStartingFrameIn?(a.modifyElements(a.nextFrameChildren,"0s"),a.nextFrame.addClass("animate-out"),a.goTo(a.nextFrameID,-1)):a.goTo(a.nextFrameID,1):(a.currentFrameID=a.nextFrameID,a.settings.moveActiveFrameToTop&&a.nextFrame.css("z-index",a.numberOfFrames),a.modifyElements(a.nextFrameChildren,"0s"),a.nextFrame.addClass("animate-in"),a.settings.hashTags&&a.settings.hashChangesOnFirstFrame&&(a.currentHashTag=a.nextFrame.attr(a.getHashTagFrom),document.location.hash="#"+a.currentHashTag),
setTimeout(function(){a.modifyElements(a.nextFrameChildren,"")},100),a.resetAutoPlay(!0,a.settings.autoPlayDelay)):(a.container.addClass("sequence-fallback"),a.currentFrameID=a.nextFrameID,a.settings.hashTags&&a.settings.hashChangesOnFirstFrame&&(a.currentHashTag=a.nextFrame.attr(a.getHashTagFrom),document.location.hash="#"+a.currentHashTag),a.sequence.children("li").addClass("animate-in"),a.sequence.children(":not(li:nth-child("+a.nextFrameID+"))").css({display:"none",opacity:0}),a.resetAutoPlay(!0,
a.settings.autoPlayDelay));void 0!==a.nextButton&&a.nextButton.click(function(){a.next()});void 0!==a.prevButton&&a.prevButton.click(function(){a.prev()});void 0!==a.pauseButton&&a.pauseButton.click(function(){a.pause(!0)});if(a.settings.keyNavigation){var b={left:37,right:39},c=function(l,c){var d;for(keyCodes in c)d="left"===keyCodes||"right"===keyCodes?b[keyCodes]:keyCodes,l===parseFloat(d)&&a.initCustomKeyEvent(c[keyCodes])};e(document).keydown(function(b){var d=String.fromCharCode(b.keyCode);
0<d&&d<=a.numberOfFrames&&a.settings.numericKeysGoToFrames&&(a.nextFrameID=d,a.goTo(a.nextFrameID));c(b.keyCode,a.settings.keyEvents);c(b.keyCode,a.settings.customKeyEvents)})}if(a.settings.pauseOnHover&&a.settings.autoPlay&&!a.hasTouch)a.sequence.on({mouseenter:function(){a.mouseover=!0;a.isHardPaused||a.pause()},mouseleave:function(){a.mouseover=!1;a.isHardPaused||a.unpause()}});a.settings.hashTags&&e(window).hashchange(function(){newTag=location.hash.replace("#","");a.currentHashTag!==newTag&&
(a.currentHashTag=newTag,a.frameHashIndex=e.inArray(a.currentHashTag,a.frameHashID),-1!==a.frameHashIndex&&(a.nextFrameID=a.frameHashIndex+1,a.goTo(a.nextFrameID)))});if(a.settings.swipeNavigation&&a.hasTouch){var d,g,f=!1,h=function(){a.sequence.on("touchmove",k);d=null;f=!1},k=function(b){a.settings.swipePreventsDefault&&b.preventDefault();if(f){var c=d-b.originalEvent.touches[0].pageX;b=g-b.originalEvent.touches[0].pageY;Math.abs(c)>=a.settings.swipeThreshold?(h(),0<c?(a.initCustomKeyEvent(a.settings.swipeEvents.left)):(a.initCustomKeyEvent(a.settings.swipeEvents.right))):Math.abs(b)>=a.settings.swipeThreshold&&(h(),0<b?a.initCustomKeyEvent(a.settings.swipeEvents.down):a.initCustomKeyEvent(a.settings.swipeEvents.up))}};a.sequence.on("touchstart",function(b){1==b.originalEvent.touches.length&&(d=b.originalEvent.touches[0].pageX,g=b.originalEvent.touches[0].pageY,f=!0,a.sequence.on("touchmove",k))})}}var a=this;a.container=e(c);a.sequence=a.container.children("ul");
try{if(Modernizr.prefixed,void 0===Modernizr.prefixed)throw"undefined";}catch(w){g.modernizr()}a.prefix={WebkitTransition:"-webkit-",MozTransition:"-moz-",OTransition:"-o-",msTransition:"-ms-",transition:""}[Modernizr.prefixed("transition")];a.transitionEnd={WebkitTransition:"webkitTransitionEnd webkitAnimationEnd",MozTransition:"transitionend animationend",OTransition:"otransitionend oanimationend",msTransition:"MSTransitionEnd MSAnimationEnd",transition:"transitionend animationend"}[Modernizr.prefixed("transition")];
a.transitionProperties={};a.numberOfFrames=a.sequence.children("li").length;a.transitionsSupported=void 0!==a.prefix?!0:!1;a.hasTouch="ontouchstart"in window?!0:!1;a.active;a.navigationSkipThresholdActive=!1;a.autoPlayTimer;a.isPaused=!1;a.isHardPaused=!1;a.mouseover=!1;a.defaultPreloader;a.nextButton;a.prevButton;a.pauseButton;a.pauseIcon;a.delayUnpause;a.init={uiElements:function(b,c){switch(b){case !1:break;case !0:return".sequence-preloader"===c&&g.defaultPreloader(a.container,a.transitionsSupported,
a.prefix),e(c);default:return e(b)}}};a.paused=function(){};a.unpaused=function(){};a.beforeNextFrameAnimatesIn=function(){};a.afterNextFrameAnimatesIn=function(){};a.beforeCurrentFrameAnimatesOut=function(){};a.afterCurrentFrameAnimatesOut=function(){};a.afterLoaded=function(){};a.settings=e.extend({},b,d);a.settings.preloader=a.init.uiElements(a.settings.preloader,".sequence-preloader");a.firstFrame=a.settings.animateStartingFrameIn?!0:!1;a.settings.unpauseDelay=null===a.settings.unpauseDelay?a.settings.autoPlayDelay:
a.settings.unpauseDelay;a.currentHashTag;a.getHashTagFrom=a.settings.hashDataAttribute?"data-sequence-hashtag":"id";a.frameHashID=[];a.direction=a.settings.autoPlayDirection;a.settings.hideFramesUntilPreloaded&&a.settings.preloader&&a.sequence.children("li").hide();"-o-"===a.prefix&&(a.transitionsSupported=g.operaTest());a.modifyElements(a.sequence.children("li"),"0s");a.sequence.children("li").removeClass("animate-in");b=a.settings.preloadTheseFrames.length;c=a.settings.preloadTheseImages.length;
!a.settings.preloader||0===b&&0===c?e(window).bind("load",function(){h();e(this).unbind("load")}):(d=function(b,c){var d=[];if(c)for(g=b;0<g;g--)d.push(e("body").find('img[src="'+a.settings.preloadTheseImages[g-1]+'"]')[0]);else for(var g=b;0<g;g--)a.sequence.children("li:nth-child("+a.settings.preloadTheseFrames[g-1]+")").find("img").each(function(){d.push(e(this)[0])});return d},b=d(b),c=d(c,!0),c=e(b.concat(c)),f(c,h))}m.prototype={initCustomKeyEvent:function(c){switch(c){case "next":this.next();
break;case "prev":this.prev();break;case "pause":this.pause(!0)}},modifyElements:function(c,d){c.css(this.prefixCSS(this.prefix,{"transition-duration":d,"transition-delay":d}))},prefixCSS:function(c,d){var b={};for(property in d)b[c+property]=d[property];return b},setTransitionProperties:function(c){var d=this;c.each(function(){d.transitionProperties["transition-duration"]=e(this).css(d.prefix+"transition-duration");d.transitionProperties["transition-delay"]=e(this).css(d.prefix+"transition-delay");
e(this).css(d.prefixCSS(d.prefix,d.transitionProperties))})},startAutoPlay:function(c){var d=this;c=void 0===c?d.settings.autoPlayDelay:c;d.unpause();d.resetAutoPlay();d.autoPlayTimer=setTimeout(function(){1===d.settings.autoPlayDirection?d.next():d.prev()},c)},stopAutoPlay:function(){this.pause(!0);clearTimeout(this.autoPlayTimer)},resetAutoPlay:function(c,d){var b=this;!0===c?b.settings.autoPlay&&!b.isPaused&&(clearTimeout(b.autoPlayTimer),b.autoPlayTimer=setTimeout(function(){1===b.settings.autoPlayDirection?
b.next():b.prev()},d)):clearTimeout(b.autoPlayTimer)},pause:function(c){this.isPaused?this.unpause():(void 0!==this.pauseButton&&(this.pauseButton.addClass("paused"),void 0!==this.pauseIcon&&this.pauseIcon.show()),this.paused(),this.isPaused=!0,this.isHardPaused=c?!0:!1,this.resetAutoPlay())},unpause:function(c){void 0!==this.pauseButton&&(this.pauseButton.removeClass("paused"),void 0!==this.pauseIcon&&this.pauseIcon.hide());this.isHardPaused=this.isPaused=!1;this.active?this.delayUnpause=!0:(!1!==
c&&this.unpaused(),this.resetAutoPlay(!0,this.settings.unpauseDelay))},next:function(){this.nextFrameID=this.currentFrameID!==this.numberOfFrames?this.currentFrameID+1:1;this.goTo(this.nextFrameID,1)},prev:function(){this.nextFrameID=1===this.currentFrameID?this.numberOfFrames:this.currentFrameID-1;this.goTo(this.nextFrameID,-1)},goTo:function(c,d){var b=this;c=parseFloat(c);if(c===b.currentFrameID||b.settings.navigationSkip&&b.navigationSkipThresholdActive||!b.settings.navigationSkip&&b.active||
!b.transitionsSupported&&b.active||!b.settings.cycle&&1===d&&b.currentFrameID===b.numberOfFrames||!b.settings.cycle&&-1===d&&1===b.currentFrameID||b.settings.preventReverseSkipping&&b.direction!==d&&b.active)return!1;b.settings.navigationSkip&&b.active&&(b.navigationSkipThresholdActive=!0,b.settings.fadeFrameWhenSkipped&&b.nextFrame.stop().animate({opacity:0},b.settings.fadeFrameTime),navigationSkipThresholdTimer=setTimeout(function(){b.navigationSkipThresholdActive=!1},b.settings.navigationSkipThreshold));
if(!b.active||b.settings.navigationSkip){b.active=!0;b.resetAutoPlay();b.direction=void 0===d?c>b.currentFrameID?1:-1:d;b.currentFrame=b.sequence.children(".animate-in");b.nextFrame=b.sequence.children("li:nth-child("+c+")");b.frameChildren=b.currentFrame.children();b.nextFrameChildren=b.nextFrame.children();if(b.transitionsSupported)void 0!==b.currentFrame.length?(b.beforeCurrentFrameAnimatesOut(),b.settings.moveActiveFrameToTop&&b.currentFrame.css("z-index",1),b.modifyElements(b.nextFrameChildren,
"0s"),b.settings.reverseAnimationsWhenNavigatingBackwards&&1!==b.direction)?b.settings.reverseAnimationsWhenNavigatingBackwards&&-1===b.direction&&(b.nextFrame.addClass("animate-out"),b.setTransitionProperties(b.frameChildren)):(b.nextFrame.removeClass("animate-out"),b.modifyElements(b.frameChildren,"")):b.firstFrame=!1,b.active=!0,b.currentFrame.unbind(b.transitionEnd),b.nextFrame.unbind(b.transitionEnd),b.settings.fadeFrameWhenSkipped&&b.nextFrame.css("opacity",1),b.beforeNextFrameAnimatesIn(),
b.settings.moveActiveFrameToTop&&b.nextFrame.css({"z-index":b.numberOfFrames}),b.settings.reverseAnimationsWhenNavigatingBackwards&&1!==b.direction?b.settings.reverseAnimationsWhenNavigatingBackwards&&-1===b.direction&&setTimeout(function(){b.modifyElements(b.nextFrameChildren,"");b.setTransitionProperties(b.frameChildren);b.waitForAnimationsToComplete(b.nextFrame,b.nextFrameChildren,"in");"function () {}"!=b.afterCurrentFrameAnimatesOut&&b.waitForAnimationsToComplete(b.currentFrame,b.frameChildren,
"out")},50):setTimeout(function(){b.modifyElements(b.nextFrameChildren,"");b.waitForAnimationsToComplete(b.nextFrame,b.nextFrameChildren,"in");"function () {}"!==b.afterCurrentFrameAnimatesOut&&b.waitForAnimationsToComplete(b.currentFrame,b.frameChildren,"out")},50),b.settings.reverseAnimationsWhenNavigatingBackwards&&1!==b.direction?b.settings.reverseAnimationsWhenNavigatingBackwards&&-1===b.direction&&setTimeout(function(){b.nextFrame.toggleClass("animate-out animate-in");b.currentFrame.removeClass("animate-in")},
50):setTimeout(function(){b.currentFrame.toggleClass("animate-out animate-in");b.nextFrame.addClass("animate-in")},50);else{var e=function(){b.setHashTag();b.active=!1;b.resetAutoPlay(!0,b.settings.autoPlayDelay)};switch(b.settings.fallback.theme){case "fade":b.sequence.children("li").css({position:"relative"});b.beforeCurrentFrameAnimatesOut();b.currentFrame=b.sequence.children("li:nth-child("+b.currentFrameID+")");b.currentFrame.animate({opacity:0},b.settings.fallback.speed,function(){b.currentFrame.css({display:"none",
"z-index":"1"});b.afterCurrentFrameAnimatesOut();b.beforeNextFrameAnimatesIn();b.nextFrame.css({display:"block","z-index":b.numberOfFrames}).animate({opacity:1},500,function(){b.afterNextFrameAnimatesIn()});e()});b.sequence.children("li").css({position:"relative"});break;default:var h={},f={},k={};1===b.direction?(h.left="-100%",f.left="100%"):(h.left="100%",f.left="-100%");k.left="0";k.opacity=1;b.currentFrame=b.sequence.children("li:nth-child("+b.currentFrameID+")");b.beforeCurrentFrameAnimatesOut();
b.currentFrame.animate(h,b.settings.fallback.speed,function(){b.afterCurrentFrameAnimatesOut()});b.beforeNextFrameAnimatesIn();b.nextFrame.show().css(f);b.nextFrame.animate(k,b.settings.fallback.speed,function(){e();b.afterNextFrameAnimatesIn()})}}b.currentFrameID=c}},waitForAnimationsToComplete:function(c,d,b){var g=this;if("out"===b)var h=function(){g.afterCurrentFrameAnimatesOut()};else"in"===b&&(h=function(){g.afterNextFrameAnimatesIn();g.setHashTag();g.active=!1;g.isHardPaused||g.mouseover||
(g.delayUnpause?(g.delayUnpause=!1,g.unpause()):g.unpause(!1))});d.data("animationEnded",!1);c.bind(g.transitionEnd,function(b){e(b.target).data("animationEnded",!0);var k=!0;d.each(function(){if(!1===e(this).data("animationEnded"))return k=!1});k&&(c.unbind(g.transitionEnd),h())})},setHashTag:function(){this.settings.hashTags&&(this.currentHashTag=this.nextFrame.attr(this.getHashTagFrom),this.frameHashIndex=e.inArray(this.currentHashTag,this.frameHashID),-1===this.frameHashIndex||!this.settings.hashChangesOnFirstFrame&&
this.firstFrame&&this.transitionsSupported?(this.nextFrameID=this.settings.startingFrameID,this.firstFrame=!1):(this.nextFrameID=this.frameHashIndex+1,document.location.hash="#"+this.currentHashTag))}};e.fn.sequence=function(c){return this.each(function(){var d=new m(e(this),c,y,z);e(this).data("sequence",d)})};var z={modernizr:function(){window.Modernizr=function(c,d,b){function e(c,d){for(var g in c){var f=c[g];if(!~(""+f).indexOf("-")&&a[f]!==b)return"pfx"==d?f:!0}return!1}function h(a,c,d){var f=
a.charAt(0).toUpperCase()+a.slice(1),h=(a+" "+w.join(f+" ")+f).split(" ");if("string"===typeof c||"undefined"===typeof c)c=e(h,c);else a:{h=(a+" "+m.join(f+" ")+f).split(" "),a=h;for(var k in a)if(f=c[a[k]],f!==b){c=!1===d?a[k]:"function"===typeof f?f.bind(d||c):f;break a}c=!1}return c}var f={},k=d.documentElement;c=d.createElement("modernizr");var a=c.style,w=["Webkit","Moz","O","ms"],m=["webkit","moz","o","ms"];c={};var x=[],r=x.slice,q,t={}.hasOwnProperty,p;"undefined"===typeof t||"undefined"===
typeof t.call?p=function(a,b){return b in a&&"undefined"===typeof a.constructor.prototype[b]}:p=function(a,b){return t.call(a,b)};Function.prototype.bind||(Function.prototype.bind=function(a){var b=self;if("function"!=typeof b)throw new TypeError;var c=r.call(arguments,1),d=function(){if(self instanceof d){var e=function(){};e.prototype=b.prototype;var e=new e,f=b.apply(e,c.concat(r.call(arguments)));return Object(f)===f?f:e}return b.apply(a,c.concat(r.call(arguments)))};return d});c.svg=function(){return!!d.createElementNS&&
!!d.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect};for(var v in c)p(c,v)&&(q=v.toLowerCase(),f[q]=c[v](),x.push((f[q]?"":"no-")+q));f.addTest=function(a,c){if("object"==typeof a)for(var d in a)p(a,d)&&f.addTest(d,a[d]);else{a=a.toLowerCase();if(f[a]!==b)return f;c="function"==typeof c?c():c;enableClasses&&(k.className+=" "+(c?"":"no-")+a);f[a]=c}return f};a.cssText="";return c=null,f._version="2.6.1",f._domPrefixes=m,f._cssomPrefixes=w,f.testProp=function(a){return e([a])},f.testAllProps=
h,f.prefixed=function(a,b,c){return b?h(a,b,c):h(a,"pfx")},f}(self,self.document)},defaultPreloader:function(c,d,b){e("head").append("<style>.sequence-preloader{height: 100%;position: absolute;width: 100%;z-index: 999999;}@"+b+"keyframes preload{0%{opacity: 1;}50%{opacity: 0;}100%{opacity: 1;}}.sequence-preloader .preloading .circle{fill: #ff9442;display: inline-block;height: 12px;position: relative;top: -50%;width: 12px;"+b+"animation: preload 1s infinite; animation: preload 1s infinite;}.preloading{display:block;height: 12px;margin: 0 auto;top: 50%;margin-top:-6px;position: relative;width: 48px;}.sequence-preloader .preloading .circle:nth-child(2){"+
b+"animation-delay: .15s; animation-delay: .15s;}.sequence-preloader .preloading .circle:nth-child(3){"+b+"animation-delay: .3s; animation-delay: .3s;}.preloading-complete{opacity: 0;visibility: hidden;"+b+"transition-duration: 1s; transition-duration: 1s;}div.inline{background-color: #ff9442; margin-right: 4px; float: left;}</style>");c.prepend('<div class="sequence-preloader"><svg class="preloading" xmlns="http://www.w3.org/2000/svg"><circle class="circle" cx="6" cy="6" r="6" /><circle class="circle" cx="22" cy="6" r="6" /><circle class="circle" cx="38" cy="6" r="6" /></svg></div>');
Modernizr.svg||d?d||setInterval(function(){e(".sequence-preloader").fadeToggle(500)},500):(e(".sequence-preloader").prepend('<div class="preloading"><div class="circle inline"></div><div class="circle inline"></div><div class="circle inline"></div></div>'),setInterval(function(){e(".sequence-preloader .circle").fadeToggle(500)},500))},operaTest:function(){e("body").append('<span id="sequence-opera-test"></span>');var c=e("#sequence-opera-test");c.css("-o-transition","1s");return"1s"!=c.css("-o-transition")?
!1:!0}},y={startingFrameID:1,cycle:!0,animateStartingFrameIn:!1,reverseAnimationsWhenNavigatingBackwards:!0,moveActiveFrameToTop:!0,autoPlay:!0,autoPlayDirection:1,autoPlayDelay:5E3,navigationSkip:!0,navigationSkipThreshold:250,fadeFrameWhenSkipped:!0,fadeFrameTime:150,preventReverseSkipping:!1,nextButton:!1,showNextButtonOnInit:!0,prevButton:!1,showPrevButtonOnInit:!0,pauseButton:!1,unpauseDelay:null,pauseOnHover:!0,pauseIcon:!1,preloader:!1,preloadTheseFrames:[1],preloadTheseImages:[],hideFramesUntilPreloaded:!0,
prependPreloadingComplete:!0,hidePreloaderUsingCSS:!0,hidePreloaderDelay:0,keyNavigation:!0,numericKeysGoToFrames:!0,keyEvents:{left:"prev",right:"next"},customKeyEvents:{},swipeNavigation:!0,swipeThreshold:20,swipePreventsDefault:!1,swipeEvents:{left:"prev",right:"next",up:!1,down:!1},hashTags:!1,hashDataAttribute:!1,hashChangesOnFirstFrame:!1,fallback:{theme:"slide",speed:500}}})(jQuery);
(function($){
	/* IE triggers resize all over the place, so we check actual window dimensions */
	var windowHeight = jQuery(window).height(),
		windowWidth = jQuery(window).width(),
		buSlideshows = {},
		rotator, imgHeight;

		/**
		 * Resizes slideshow and all slides to height of highest slide
		 * 
		 * I hate iterating through everything in the slides here, but we should allow 
		 * for markup other than what the plugin currently produces (e.g. video, custom HTML).
		 */
		function bu_resize_slideshow() {

			$('.bu-slideshow-container').each(function(){
				var slides = $(this).find('li .bu-slide-container'), 
					$el, height = 0, currentHeight = 0;

				slides.find('*').each(function(i, el) {
					$el = $(el);
					
					currentHeight = $el.height();
					if (currentHeight > height) {
						height = currentHeight;
					}
				});

				slides.each(function(i, el) {
					$(el).height(height);
				});

				$(this).height(height);
				$(this).find('ul.bu-slideshow').height(height);
			
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