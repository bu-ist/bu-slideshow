(function(a){function l(c,f){a("#bu-slideshow-editform-submit").prop("disabled","disabled");window.reindexingSlides=!0;var e=/bu_slides\[([0-9]*)\](.*)/,g="";a(".bu-slideshow-slide").each(function(c,f){a(f).find("input, textarea, select").each(function(d,b){var n=a(b);g=n.attr("name");g=g.replace(e,"bu_slides["+c+"]$2");n.attr("name",g)})});a("#bu-slideshow-editform-submit").prop("disabled",!1);window.reindexingSlides=!1}function q(c,f){var e=f.item.height(),g=f.item.width();a(".sortable-placeholder").outerHeight(e).width(g)}
function r(c){a.post(ajaxurl,{action:"bu_add_slide",order:c},function(c){c?(c=a(c),c.appendTo("#bu-slideshow-slidelist ul"),p(c.find(".bu-slideshow-add-img")),a("#bu-slideshow-slidelist ul li:last-child .bu-slide-edit-container").slideDown(),a(".slide-added-confirmation").fadeIn().delay(3E3).fadeOut()):h(buSlideshowLocalAdmin.addSlideFailError,a("#bu-slideshow-slidelist"))})}function p(c){imgHref=c.attr("href")+"&width=640&height="+0.9*a(window).height();c.attr("href",imgHref)}function h(c,f,e){"undefined"===
typeof e&&(e=!1);a(".error").remove();c='<div class="error"><p>'+c+"</p></div>";e?f.prepend(c):f.append(c);setTimeout(function(){a(".error").fadeOut(500)},1E3)}a(function(){var c=a("#bu-slideshow-newform"),f=a("#bu-slideshow-manage"),e=a("#bu-slideshow-slides"),g=a("#bu-slideshow-edit");if(c.length)c.on("submit",function(){return a("#bu-new-slideshow-name").val().length?!0:(h("You must enter a name.",c),!1)});f.length&&e.length&&function(d){var b={};b.container=e;b.list=a(d.list);if(!b.list.length)throw TypeError("Invalid element supplied to slideshow manager.");
b.nonce=a("#bu_slideshow_nonce").val();b.numShows=function(){return b.list.find("li").length};b.addEmptyMsg=function(){b.getUrl("add_url",function(a){b.list.append("<li><p>"+buSlideshowLocalAdmin.noSlideshowsMsg+'</p><p><a class="button" href="'+a+'">'+buSlideshowLocalAdmin.addButtonText+"</a></p></li>")})};b.getUrl=function(d,b){a.post(ajaxurl,{action:"bu_slideshow_get_url",url:d},function(a){b(a)})};b.list.on("click",".bu-slideshow-delete",function(){if(confirm(buSlideshowLocalAdmin.deleteConfirm)){var d=
a(this),c;c={action:"bu_delete_slideshow",id:d.attr("data-slideshow-id"),bu_slideshow_nonce:b.nonce};a.post(ajaxurl,c,function(a){b.deleteResponse(d,a)})}return!1});b.deleteResponse=function(a,d){if(d&&"0"!==d)a.parent().parent("li").remove(),b.container.find(".error").remove(),b.numShows()||b.addEmptyMsg();else return h(buSlideshowLocalAdmin.deleteError,b.container),!1}}({list:"#bu-slideshow-manage"});if(a("#bu_slideshow_modal_button").length&&"function"===typeof BuModal&&"function"===typeof SlideshowSelector){var m=
new BuModal({el:"#bu_slideshow_modal_wrap"}),k=new SlideshowSelector("#bu_slideshow_modal_wrap .bu-slideshow-selector");a("#bu_slideshow_modal_button").on("click",function(){m.open()});a("#bu_slideshow_modal_wrap").on("click","#bu_insert_slideshow",function(a){k.ui.parent().find(".error").remove();a=k.getOptions();if(!parseInt(a.show_id))return h(buSlideshowLocalAdmin.noneSelectedError,k.ui.parent()),!1;window.send_to_editor("<br />"+('[bu_slideshow show_id="'+a.show_id+'" show_nav="'+a.show_nav+
'" transition="'+a.transition+'" nav_style="'+a.nav_style+'" autoplay="'+a.autoplay+'" transition_delay="'+a.transition_delay+'" width="'+a.width+'"]')+"<br />");k.reset();m.close();return!1})}g.length&&(a(".bu-slideshow-slide:first-child .bu-slide-control").addClass("open"),a("#bu-slideshow-slidelist").on("click",".bu-slide-expand",function(){var d=a(this),b;b=d.parents(".bu-slideshow-slide").find(".bu-slide-edit-container");d.hasClass("open")?(d.removeClass("open"),b.slideUp(300)):(d.addClass("open"),
b.slideDown(300));return!1}),a("#bu-slideshow-slidelist").on("keyup",".bu-slideshow-title-input",function(){var d=a(this);d.parents(".bu-slideshow-slide").find(".bu-slide-title").text(d.val())}),a("#bu-slideshow-editform").on("submit",function(){return a("#bu_slideshow_name").val().replace(" ","")?!window.reindexingSlides:(h(buSlideshowLocalAdmin.emptyNameError,a(this)),!1)}),a("#bu-slideshow-slidelist ul").sortable({stop:l,placeholder:"sortable-placeholder",start:q}),a("#bu-slideshow-add-slide").on("click",
function(){var a=g.find("#bu-slideshow-slidelist li").length;r(a);return!1}),a("#bu-slideshow-slidelist").on("click",".bu-slide-delete-button",function(){confirm(buSlideshowLocalAdmin.deleteConfirmSlide)&&(a(this).parents().parent(".bu-slideshow-slide").remove(),l());return!1}),window.buUploaders={newEditor:window.wp&&window.wp.media?!0:!1,init:function(d){d=a(d);if(!d.length)throw new TypeError("No valid button identified.");this.slide=d.parents(".bu-slideshow-slide");this.populateFields()},populateFields:function(){this.addButton=
this.slide.find(".bu-slideshow-add-img");this.removeButton=this.slide.find(".bu-slideshow-remove-img");this.imgIdField=this.slide.find(".bu-slideshow-img-id");this.imgSizeField=this.slide.find(".bu-slideshow-img-size");this.imgMeta=this.slide.find(".bu-slide-meta");this.thumbContainers=this.slide.find(".bu-slide-thumb, .bu-slide-header-thumb")},select:function(){this.newEditor?this.newHandleImageSelect():this.oldHandleImageSelect()},remove:function(){this.thumbContainers.each(function(d,b){a(b).find("img").remove()});
this.imgIdField.val("");this.imgSizeField.val("");this.imgMeta.hide();this.removeButton.hide()},handleImgThumbResponse:function(d){var b,c;(d=a.parseJSON(d))&&"0"!==d?(this.thumbContainers.each(function(f,e){c=a(e);b=c.find("img");b.length?b.attr("src",d[0]):c.append('<img src="'+d[0]+'" alt="'+buSlideshowLocalAdmin.thumbAltText+'" />')}),this.removeButton.show()):h(buSlideshowLocalAdmin.thumbFailError,this.slide.find(".bu-slide-edit-container"),!0)},newHandleImageSelect:function(){var a=this;if("object"!==
typeof buUploadFrame){var b=wp.media.view.MediaFrame.Select.prototype.browseContent;a.modifyWPSelectFrame();buUploadFrame=wp.media.frames.bu_slideshow_frame=wp.media({multiple:!1,title:buSlideshowLocalAdmin.mediaUploadTitle,button:{text:buSlideshowLocalAdmin.mediaUploadButton},library:{type:"image"}});wp.media.view.MediaFrame.Select.prototype.browseContent=b;buUploadFrame.on("select",function(){var b,c;c=buUploadFrame.state();b=c.get("selection").first();c=c.display(b).attributes;b=b.toJSON().id;
a.getImgThumb(b);a.setImageDetails(b,c.size);a.slide.find(".bu-slide-meta").hide()})}buUploadFrame.open()},modifyWPSelectFrame:function(){wp.media.view.MediaFrame.Select.prototype.browseContent=function(a){var b=this.state(),c=b.get("selection");this.$el.removeClass("hide-toolbar");a.view=new wp.media.view.AttachmentsBrowser({controller:this,collection:b.get("library"),model:b,filters:b.get("filterable"),display:!0,selection:c,sortable:!0,search:!1,dragInfo:!0,AttachmentView:wp.media.view.Attachment.EditLibrary})}},
getImgThumb:function(c){var b=this;a.post(ajaxurl,{action:"bu_get_slide_thumb",image_id:c},function(a){b.handleImgThumbResponse(a)})},setImageDetails:function(a,b){this.imgIdField.val(a);this.imgSizeField.val(b)}},a("#bu-slideshow-slidelist").on("click",".bu-slideshow-add-img",function(a){window.buUploaders.init(this);window.buUploaders.select();return!1}),a("#bu-slideshow-slidelist").on("click",".bu-slideshow-remove-img",function(){window.buUploaders.init(this);window.buUploaders.remove();return!1}),
a("#bu-slideshow-slidelist .bu-slideshow-add-img").each(function(){p(a(this))}));a("#bu_slideshow_show_nav").on("click",function(){a(".bu_slideshow_nav_style").toggle()})})})(jQuery);
