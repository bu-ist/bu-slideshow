windowHeight=jQuery(window).height();windowWidth=jQuery(window).width();var retry_count=0; jQuery(document).ready(function(c){function e(){c(".bu-slideshow-container").each(function(){var e=c(this),a=e.find("li .bu-slide-container"),b,d=0,f=0;a.find("*").each(function(a,e){b=c(e);f=b.height();f>d&&(d=f)});a.each(function(a,b){c(b).height(d)});e.height(d);e.find("ul.bu-slideshow").height(d)})}window.buSlideshows={};window.BuSlideshow=function a(b){if(!(this instanceof a))throw new ReferenceError('Invoked constructor as regular function. Use the "new" operator.');if(!b.show)throw new TypeError("Did not pass a valid Sequence object."); if(!b.container)throw new ReferenceError("Did not pass a valid container element.");this.sequence=b.show;this.container=b.container;this.init(b)};BuSlideshow.prototype.init=function(a){var b=this;a||(a={});this.sequence.afterLoaded=e;this.sequence.beforeNextFrameAnimatesIn=function(){b.pager&&b.pager.setActive()};(this.pager=c("#"+a.pager).length?c("#"+a.pager):!1)&&this.initPager();(this.arrows=c("#"+a.arrows).length?c("#"+a.arrows):!1)&&this.initArrows()};BuSlideshow.prototype.initPager=function(){var a= this;this.pager.find("li a").bind("click",function(){var b=c(this).attr("id").replace("pager-","");a.sequence.nextFrameID=b;a.sequence.goTo(b);return!1});this.pager.setActive=function(b){b=a.sequence.nextFrameID;this.find("a").removeClass("active");this.find("a#pager-"+b).addClass("active")}};BuSlideshow.prototype.initArrows=function(){var a=this;this.arrows.find(".bu-slideshow-arrow-left").bind("click",function(){a.sequence.prev();return!1}).end().find(".bu-slideshow-arrow-right").bind("click",function(){a.sequence.next(); return!1})};c(".bu-slideshow-container").each(function(a,b){var d=c(this),f=!1,g,h,k,l;g=d.find(".bu-slideshow-slides");h=d.find("ul.bu-slideshow-navigation").attr("id");k=d.find("div.bu-slideshow-arrows").attr("id");l=d.attr("data-slideshow-name")?d.attr("data-slideshow-name"):a;d.hasClass("autoplay")&&(f=!0);d={show:g.sequence({autoPlay:f,fallback:{theme:"slide"},swipeEvents:{left:"next",right:"prev"}}).data("sequence"),container:g,pager:h,arrows:k};try{buSlideshows[l]=new BuSlideshow(d)}catch(m){0== retry_count&&'Invoked constructor as regular function. Use the "new" operator.'==m&&(setTimeout(function(){e()},1E3),retry_count++)}});c(window).resize(function(){var a,b;a=c(window).height();b=c(window).width();if(a!==windowHeight||b!==windowWidth)windowHeight=a,windowWidth=b,e()})});