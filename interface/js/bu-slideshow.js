(function(c){function f(){c(".bu-slideshow-container").each(function(){var a=c(this).find("li .bu-slide-container"),b,g=0,d=0;a.find("*").each(function(a,e){b=c(e);d=b.height();d>g&&(g=d)});a.each(function(a,b){c(b).height(g)});c(this).height(g);c(this).find("ul.bu-slideshow").height(g)})}function e(a){if(!(this instanceof e))throw new ReferenceError('Invoked constructor as regular function. Use the "new" operator.');if(!a.show)throw new TypeError("Did not pass a valid Sequence object.");if(!a.container)throw new ReferenceError("Did not pass a valid container element.");
this.sequence=a.show;this.container=a.container;this.init(a)}var h=jQuery(window).height(),k=jQuery(window).width();window.BuSlideshow=e;e.prototype.init=function(a){var b=this;a||(a={});this.sequence.afterLoaded=function(){var a=b.container.parent("div.bu-slideshow-container");a.find(".slideshow-loader.active").removeClass("active");a.find(".bu-slideshow-navigation-container").css("display","inline-block");f()};this.sequence.beforeNextFrameAnimatesIn=function(){b.pager&&b.pager.setActive()};(this.pager=
c("#"+a.pager).length?c("#"+a.pager):!1)&&this.initPager();(this.arrows=c("#"+a.arrows).length?c("#"+a.arrows):!1)&&this.initArrows()};e.prototype.initPager=function(){var a=this;this.pager.find("li a").bind("click",function(){var b=c(this).attr("id").replace("pager-","");a.sequence.nextFrameID=b;a.sequence.goTo(b);return!1});this.pager.setActive=function(b){b=a.sequence.nextFrameID;this.find("a").removeClass("active");this.find("a#pager-"+b).addClass("active")}};e.prototype.initArrows=function(){var a=
this;this.arrows.find(".bu-slideshow-arrow-left").bind("click",function(){a.sequence.prev();return!1}).end().find(".bu-slideshow-arrow-right").bind("click",function(){a.sequence.next();return!1})};jQuery(document).ready(function(a){a(".bu-slideshow-container").each(function(b,c){var d=a(this),f=!1,h,l,k,m;l=d.find(".bu-slideshow-slides");k=d.find("ul.bu-slideshow-navigation").attr("id");m=d.find("div.bu-slideshow-arrows").attr("id");d.attr("data-slideshow-name")&&d.attr("data-slideshow-name");h=d.attr("data-slideshow-delay")?
d.attr("data-slideshow-delay"):5E3;d.hasClass("autoplay")&&(f=!0);d={show:l.sequence({autoPlay:f,autoPlayDelay:h,fallback:{theme:"slide"},swipeEvents:{left:"next",right:"prev"}}).data("sequence"),container:l,pager:k,arrows:m};try{new e(d)}catch(n){}});a(window).resize(function(){var b,c;b=a(window).height();c=a(window).width();if(b!==h||c!==k)h=b,k=c,f()})})})(jQuery);
