windowHeight=jQuery(window).height();windowWidth=jQuery(window).width(); jQuery(document).ready(function(c){function f(){c(".bu-slideshow-container").each(function(){var h=c(this),a=h.find("li .bu-slide-container"),b,d=0,e=0;a.find("*").each(function(a,g){b=c(g);e=b.height();e>d&&(d=e)});a.each(function(a,b){c(b).height(d)});h.height(d);h.find("ul.bu-slideshow").height(d)})}window.buSlideshows={};window.BuSlideshow=function a(b){if(!(this instanceof a))throw new ReferenceError('Invoked constructor as regular function. Use the "new" operator.');if(!b.show)throw new TypeError("Did not pass a valid Sequence object."); if(!b.container)throw new ReferenceError("Did not pass a valid container element.");this.sequence=b.show;this.container=b.container;this.init(b)};BuSlideshow.prototype.init=function(a){var b=this;a||(a={});this.sequence.afterLoaded=f;this.sequence.beforeNextFrameAnimatesIn=function(){b.pager&&b.pager.setActive()};(this.pager=c("#"+a.pager).length?c("#"+a.pager):!1)&&this.initPager();(this.arrows=c("#"+a.arrows).length?c("#"+a.arrows):!1)&&this.initArrows()};BuSlideshow.prototype.initPager=function(){var a= this;this.pager.find("li a").bind("click",function(){var b=c(this).attr("id").replace("pager-","");a.sequence.nextFrameID=b;a.sequence.goTo(b);return!1});this.pager.setActive=function(b){b=a.sequence.nextFrameID;this.find("a").removeClass("active");this.find("a#pager-"+b).addClass("active")}};BuSlideshow.prototype.initArrows=function(){var a=this;this.arrows.find(".bu-slideshow-arrow-left").bind("click",function(){a.sequence.prev();return!1}).end().find(".bu-slideshow-arrow-right").bind("click",function(){a.sequence.next(); return!1})};c(".bu-slideshow-container").each(function(a,b){var d=c(this),e=!1,k,g,f,l;k=d.find(".bu-slideshow-slides");g=d.find("ul.bu-slideshow-navigation").attr("id");f=d.find("div.bu-slideshow-arrows").attr("id");l=d.attr("data-slideshow-name")?d.attr("data-slideshow-name"):a;d.hasClass("autoplay")&&(e=!0);d={show:k.sequence({autoPlay:e,fallback:{theme:"slide"}}).data("sequence"),container:k,pager:g,arrows:f};buSlideshows[l]=new BuSlideshow(d)});c(window).resize(function(){var a,b;a=c(window).height(); b=c(window).width();if(a!==windowHeight||b!==windowWidth)windowHeight=a,windowWidth=b,f()})});