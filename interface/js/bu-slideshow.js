!function(a){function b(){a(".bu-slideshow-container").each(function(){var b,c=a(this).find("li .bu-slide-container"),d=0,e=0;c.find("*").each(function(c,f){b=a(f),e=b.height(),e>d&&(d=e)}),c.each(function(b,c){a(c).height(d)}),a(this).height(d),a(this).find("ul.bu-slideshow").height(d)})}function c(a){if(!(this instanceof c))throw new ReferenceError('Invoked constructor as regular function. Use the "new" operator.');if(!a.show)throw new TypeError("Did not pass a valid Sequence object.");if(!a.container)throw new ReferenceError("Did not pass a valid container element.");this.sequence=a.show,this.container=a.container,this.init(a)}var d=jQuery(window).height(),e=jQuery(window).width(),f={};window.BuSlideshow=c,c.prototype.init=function(c){var d=this;c||(c={}),this.sequence.afterLoaded=function(){var a=d.container.parent("div.bu-slideshow-container");a.find(".slideshow-loader.active").removeClass("active"),a.find(".bu-slideshow-navigation-container").css("display","inline-block"),b()},this.sequence.beforeNextFrameAnimatesIn=function(){d.pager&&d.pager.setActive()},this.pager=!!a("#"+c.pager).length&&a("#"+c.pager),this.pager&&this.initPager(),this.arrows=!!a("#"+c.arrows).length&&a("#"+c.arrows),this.arrows&&this.initArrows()},c.prototype.initPager=function(){var b=this;this.pager.find("li a").bind("click",function(){var c=a(this).attr("id").replace("pager-","");return b.sequence.nextFrameID=c,b.sequence.goTo(c),!1}),this.pager.setActive=function(a){a=b.sequence.nextFrameID,this.find("a").removeClass("active"),this.find("a#pager-"+a).addClass("active")}},c.prototype.initArrows=function(){var a=this;this.arrows.find(".bu-slideshow-arrow-left").bind("click",function(){return a.sequence.prev(),!1}).end().find(".bu-slideshow-arrow-right").bind("click",function(){return a.sequence.next(),!1})},jQuery(document).ready(function(a){a(".bu-slideshow-container").each(function(b,d){var e,g,h,i,j,k,l,m=a(this),n=!1;e=m.find(".bu-slideshow-slides"),g=m.find("ul.bu-slideshow-navigation").attr("id"),h=m.find("div.bu-slideshow-arrows").attr("id"),k=m.attr("data-slideshow-name")?m.attr("data-slideshow-name"):b,l=m.attr("data-slideshow-delay")?m.attr("data-slideshow-delay"):5e3,m.hasClass("autoplay")&&(n=!0),i={autoPlay:n,autoPlayDelay:l,fallback:{theme:"slide"},swipeEvents:{left:"next",right:"prev"}},j={show:e.sequence(i).data("sequence"),container:e,pager:g,arrows:h};try{f[k]=new c(j)}catch(a){}}),a(window).resize(function(){var c,f;c=a(window).height(),f=a(window).width(),c===d&&f===e||(d=c,e=f,b())})})}(jQuery);