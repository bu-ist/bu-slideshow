jQuery(document).ready(function(d){window.SlideshowSelector=function a(b){if(!(this instanceof a))return new a(b);this.ui=d(b);if(!this.ui.length)return new TypeError("No selector element found.");this.init()};SlideshowSelector.prototype.init=function(){this.advancedToggle=this.ui.find(".bu-slideshow-advanced-toggle");this.advanced=this.ui.find(".bu-slideshow-selector-advanced");this.advanced.hide();this.addHandlers()};SlideshowSelector.prototype.addHandlers=function(){var a=this;a.ui.on("click", ".bu-slideshow-advanced-toggle",function(b){a.advanced.is(":hidden")?(a.advanced.slideDown(200),a.advancedToggle.text(buSlideshowLocalSelector.toggleTextHide)):(a.advanced.slideUp(200),a.advancedToggle.text(buSlideshowLocalSelector.toggleTextShow));return!1})};SlideshowSelector.prototype.getOptions=function(){var a={};a.show_id=this.ui.find("#bu_slideshow_selected").val();a.show_nav=this.ui.find("#bu_slideshow_show_nav").is(":checked")?1:0;a.transition=this.ui.find("#bu_slideshow_select_transition").val(); a.custom_transition=this.ui.find("#bu_slideshow_custom_trans").val().replace(" ","");a.nav_style=this.ui.find("#bu_slideshow_nav_style").val();a.autoplay=this.ui.find("#bu_slideshow_autoplay").is(":checked")?1:0;a.autoPlayDelay=this.ui.find("#bu_slideshow_autoplaydelay").val();a.width=this.ui.find("#bu_slideshow_width").val();if(0<a.custom_transition.length){a.transition=a.custom_transition;var b={"[":"","]":"",'"':""},c;for(c in b)a.transition=a.transition.replace(c,b[c])}0===a.width.length&&(a.width= "auto");return a};SlideshowSelector.prototype.reset=function(){var a;a=this.ui.find("#bu_slideshow_selected");a.val(a.find("option:first").val());a=this.ui.find("#bu_slideshow_select_transition");a.val(a.find("option:first").val());a=this.ui.find("#bu_slideshow_nav_style");a.val(a.find("option:first").val());this.ui.find("#bu_slideshow_width").val("");this.ui.find("#bu_slideshow_custom_trans").val("");this.ui.find("#bu_slideshow_show_nav").prop("checked",!0);this.ui.find("#bu_slideshow_custom_transition").val(""); this.ui.find("#bu_slideshow_autoplay").prop("checked",!0)}});