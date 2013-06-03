jQuery(document).ready(function(b){function f(a,b){b.find(".error").remove();b.append('<div class="error"><p>'+a+"</p></div>")}var h=b("#bu-slideshow-newform"),r=b("#bu-slideshow-manage"),j=b("#bu-slideshow-slides"),k=b("#bu-slideshow-edit");h.length&&h.submit(function(){return!b("#bu-new-slideshow-name").val().length?(f("You must enter a name.",h),!1):!0});if(r.length&&j.length){var c={};c.container=j;c.list=b("#bu-slideshow-manage");if(!c.list.length)throw TypeError("Invalid element supplied to slideshow manager."); c.nonce=b("#bu_slideshow_nonce").val();c.numShows=function(){return c.list.find("li").length};c.addEmptyMsg=function(){c.getUrl("add_url",function(a){c.list.append("<li><p>"+buSlideshowLocalAdmin.noSlideshowsMsg+'</p><p><a class="button" href="'+a+'">'+buSlideshowLocalAdmin.addButtonText+"</a></p></li>")})};c.getUrl=function(a,d){b.post(ajaxurl,{action:"bu_slideshow_get_url",url:a},function(a){d(a)})};c.list.find(".bu-slideshow-delete").live("click",function(){if(confirm(buSlideshowLocalAdmin.deleteConfirm)){var a= b(this),d;d={action:"bu_delete_slideshow",id:a.attr("data-slideshow-id"),bu_slideshow_nonce:c.nonce};b.post(ajaxurl,d,function(b){c.deleteResponse(a,b)})}return!1});c.deleteResponse=function(a,b){if(b&&"0"!==b)a.parent().parent("li").remove(),c.container.find(".error").remove(),c.numShows()||c.addEmptyMsg();else return f(buSlideshowLocalAdmin.deleteError,c.container),!1}}if(b("#bu_slideshow_modal_button").length&&"function"===typeof BuModal&&"function"===typeof SlideshowSelector){var l=new BuModal({el:"#bu_slideshow_modal_wrap"}), g=new SlideshowSelector("#bu_slideshow_modal_wrap .bu-slideshow-selector");b("#bu_slideshow_modal_button").click(function(){l.open()});b("#bu_insert_slideshow").live("click",function(){var a;g.ui.parent().find(".error").remove();a=g.getOptions();if(!parseInt(a.show_id))return f(buSlideshowLocalAdmin.noneSelectedError,g.ui.parent()),!1;window.send_to_editor("<br />"+('[bu_slideshow show_id="'+a.show_id+'" show_nav="'+a.show_nav+'" transition="'+a.transition+'" nav_style="'+a.nav_style+'" autoplay="'+ a.autoplay+'"]')+"<br />");g.reset();l.close();return!1})}if(k.length){var m,n;m=0.9*b(window).height();var p=function(){b("#bu-slideshow-editform-submit").attr("disabled","disabled");window.reindexingSlides=!0;var a=/bu_slides\[([0-9]*)\](.*)/,d="";b(".bu-slideshow-slide").each(function(c,e){b(e).find("input, textarea").each(function(e,f){var g=b(f);d=g.attr("name");d=d.replace(a,"bu_slides["+c+"]$2");g.attr("name",d)})});b("#bu-slideshow-editform-submit").removeAttr("disabled");window.reindexingSlides= !1},q=function(a){n=a.attr("href")+"&width=640&height="+m;a.attr("href",n)};b(".bu-slide-edit-container").hide();b(".bu-slide-expand").live("click",function(){var a=b(this),d;d=a.parents(".bu-slideshow-slide").find(".bu-slide-edit-container");a.hasClass("open")?(a.removeClass("open"),d.slideUp(300)):(a.addClass("open"),d.slideDown(300));return!1});b(".bu-slideshow-title-input").live("keyup",function(){var a=b(this);a.parents(".bu-slideshow-slide").find(".bu-slide-title").text(a.val())});b("#bu-slideshow-editform").live("submit", function(){return!b("#bu_slideshow_name").val().replace(" ","")?(f(buSlideshowLocalAdmin.emptyNameError,b(this)),!1):!window.reindexingSlides});b("#bu-slideshow-slidelist ul").sortable({stop:p,placeholder:"sortable-placeholder",start:function(a,d){var c=d.item.height(),e=d.item.width();b(".sortable-placeholder").outerHeight(c).width(e)}});b("#bu-slideshow-add-slide").click(function(){var a={action:"bu_add_slide",order:k.find("#bu-slideshow-slidelist li").length};b.post(ajaxurl,a,function(a){a?(a= b(a),a.find(".bu-slide-edit-container").css("display","none"),a.appendTo("#bu-slideshow-slidelist ul"),q(a.find(".bu-slideshow-add-img"))):f(buSlideshowLocalAdmin.addSlideFailError,b("#bu-slideshow-slidelist"))});return!1});b(".bu-slide-delete-button").live("click",function(){b(this).parents().parent(".bu-slideshow-slide").remove();p();return!1});window.buUploaders={newEditor:window.wp&&window.wp.media?!0:!1,init:function(a){a=b(a);if(!a.length)throw new TypeError("No valid button identified.");this.slide= a.parents(".bu-slideshow-slide");this.populateFields()},populateFields:function(){this.addButton=this.slide.find(".bu-slideshow-add-img");this.removeButton=this.slide.find(".bu-slideshow-remove-img");this.imgIdField=this.slide.find(".bu-slideshow-img-id");this.imgSizeField=this.slide.find(".bu-slideshow-img-size");this.thumbContainers=this.slide.find(".bu-slide-thumb, .bu-slide-header-thumb")},select:function(){if(this.newEditor)return!1;this.oldHandleImageSelect()},remove:function(){this.thumbContainers.each(function(a, d){b(d).find("img").remove()});this.imgIdField.val("");this.imgSizeField.val("");this.removeButton.hide()},oldHandleImageSelect:function(){var a=this;window.send_to_editor=function(d){var c,e;d=b("img",d).attr("class");c=/wp-image-([0-9]*)/i;c=c.exec(d);e=c[1];a.imgIdField.val(e);c=/size-([a-zA-Z]*)/i;c=c.exec(d);a.imgSizeField.val(c[1]);b.post(ajaxurl,{action:"bu_get_slide_thumb",image_id:e},function(b){a.handleImgThumbResponse(b)});tb_remove()}},handleImgThumbResponse:function(a){var c;!a||"0"=== a?f(buSlideshowLocalAdmin.thumbFailError,this.slide):(a=b.parseJSON(a),this.thumbContainers.each(function(f,e){c=b(e).find("img");c.length?c.attr("src",a[0]):b(e).append('<img src="'+a[0]+'" alt="'+buSlideshowLocalAdmin.thumbAltText+'" />')}),this.removeButton.show())}};b(".bu-slideshow-add-img").live("click",function(a){a.preventDefault();window.buUploaders.init(this);window.buUploaders.select();return!1});b(".bu-slideshow-remove-img").live("click",function(){window.buUploaders.init(this);window.buUploaders.remove(); return!1});b("#bu-slideshow-slidelist .bu-slideshow-add-img").each(function(){q(b(this))})}});