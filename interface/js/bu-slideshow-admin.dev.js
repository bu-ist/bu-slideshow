/*
	@todo: update oldHandleImageSelect to use $.parseHTML() [requires jQuery 1.8+]
*/
(function($){
	/* update slide input names so that new order is saved */
	function reindexSlides(event, ui) {
		$('#bu-slideshow-editform-submit').prop('disabled', 'disabled');
		window.reindexingSlides = true;
		
		var regEx = /bu_slides\[([0-9]*)\](.*)/;
		var name = '';
		
		$('.bu-slideshow-slide').each(function(index, el) {
			$(el).find('input, textarea, select').each(function(i, e) {
				var $e = $(e);
				name = $e.attr('name');
				name = name.replace(regEx, "bu_slides[" + index + "]$2");
				$e.attr('name', name);
			});
		});
		
		$('#bu-slideshow-editform-submit').prop('disabled', false);
		window.reindexingSlides = false;
		
	}

	/* Make slides sortable */
	function createPlaceholder(event, ui) {
		
		var h = ui.item.height();
		var w = ui.item.width();
		$('.sortable-placeholder').outerHeight(h).width(w);
		
	}

	function addSlide(order) {
		
		var data = {
			"action": "bu_add_slide",
			"order": order
		};
		
		$.post(ajaxurl, data, function(response) {
			if (!response) {
				displayError(buSlideshowLocalAdmin.addSlideFailError, $('#bu-slideshow-slidelist'));
				return;
			}

			var r = $(response);
			r.appendTo('#bu-slideshow-slidelist ul');
			setModalHeight( r.find('.bu-slideshow-add-img') );
			$("#bu-slideshow-slidelist ul li:last-child .bu-slide-edit-container").slideDown();
			$(".slide-added-confirmation").fadeIn().delay(3000).fadeOut();
		});
		
	}

	function setModalHeight($link) {

		imgHref = $link.attr('href') + '&width=640&height=' + ($(window).height() * 0.9);
		$link.attr('href', imgHref);

	}

	/**
	 * Removes any existing errors and displays new one. 
	 */
	function displayError(msg, target, pre) {
		if ( typeof pre === 'undefined') {
			pre = false; //append error
		}
		
		$('.error').remove();
		var html = '<div class="error"><p>' + msg + '</p></div>';
		
		if (pre) {
			target.prepend(html);
		} else {
			target.append(html);
		}
		
		setTimeout(function() {
			$('.error').fadeOut(500);
		}, 1000);
	}

	$(function(){		
		var newSlideshowForm = $('#bu-slideshow-newform'), slideShowList = $('#bu-slideshow-manage'),
			slidesContainer = $('#bu-slideshow-slides'), slideEditor = $('#bu-slideshow-edit'),
			manageUrl = 'admin.php?page=bu-slideshow',
			imgHref;
		
		/** New slideshow page */
		if (newSlideshowForm.length) {
			newSlideshowForm.on('submit', function() {
				var name = $('#bu-new-slideshow-name').val();

				if (!name.length) {
					displayError('You must enter a name.', newSlideshowForm);
					return false;
				}

				return true;
			});
		}
		
		/** 
		 * Manage slideshows page 
		 */
		if (slideShowList.length && slidesContainer.length) {
			
			var slideShowMgr = function(args) {
				var that = {};
				
				that.container = slidesContainer;
				
				that.list = $(args.list);
				if (!that.list.length) {
					throw TypeError('Invalid element supplied to slideshow manager.');
				}
				
				that.nonce = $('#bu_slideshow_nonce').val();
				
				that.numShows = function() {
					return that.list.find('li').length;
				};
				
				that.addEmptyMsg = function() {
					that.getUrl('add_url', function(addUrl) {
						var html = '<li><p>' + buSlideshowLocalAdmin.noSlideshowsMsg + '</p>' +
							'<p><a class="button" href="' + addUrl + '">' + buSlideshowLocalAdmin.addButtonText + '</a></p></li>';
						that.list.append(html);
					});
					
				};
				
				that.getUrl = function(url, cb) {
					var data = {
						"action" : "bu_slideshow_get_url",
						"url" : url
					};
					$.post(ajaxurl, data, function(response) {
						cb(response);
					});
				};
				
				that.list.on('click', '.bu-slideshow-delete', function() {
					var result = confirm(buSlideshowLocalAdmin.deleteConfirm);
					
					if (result) {
						var $this = $(this), showId, data;
						showId = $this.attr('data-slideshow-id');

						data = {
							"action": 'bu_delete_slideshow',
							"id": showId,
							"bu_slideshow_nonce": that.nonce
						};
						
						$.post(ajaxurl, data, function(response) {
							that.deleteResponse($this, response);
						});
					}

					return false;
				});
				
				that.deleteResponse = function(el, r) {
					if (r && r !== '0') {
						el.parent().parent('li').remove();
						that.container.find('.error').remove();

						if (!that.numShows()) {
							that.addEmptyMsg();
						}
					} else {
						displayError(buSlideshowLocalAdmin.deleteError, that.container);
						return false;
					}
				};
			};
			
			slideShowMgr({'list': '#bu-slideshow-manage'});
			
		}
		
		/* Add Slideshow button and Inserting shortcode into editor */
		if ($('#bu_slideshow_modal_button').length && typeof BuModal === 'function' && typeof SlideshowSelector === 'function') {
			
			var modal = new BuModal({ 'el' : '#bu_slideshow_modal_wrap' }),
				selector = new SlideshowSelector('#bu_slideshow_modal_wrap .bu-slideshow-selector');
			
			$('#bu_slideshow_modal_button').on('click', function() {
				modal.open();
			});
			
			$('#bu_slideshow_modal_wrap').on('click', '#bu_insert_slideshow', function(e) {
				var options, html;
				selector.ui.parent().find('.error').remove();
				options = selector.getOptions();

				if (!parseInt(options.show_id)) {
					displayError(buSlideshowLocalAdmin.noneSelectedError, selector.ui.parent());
					return false;
				}

				html = '[bu_slideshow show_id="' + options.show_id + '" show_nav="' + options.show_nav + '" transition="' + options.transition + '" nav_style="' + options.nav_style + '" autoplay="' + options.autoplay  + '" transition_delay="' + options.transition_delay  + '" width="' + options.width + '"]';

				window.send_to_editor("<br />" + html + "<br />");
				selector.reset();
				modal.close();
				return false;
			});
		}

		
		
		/* Edit Slideshow page */
		if (slideEditor.length) {
			


			$('.bu-slideshow-slide:first-child .bu-slide-control').addClass('open');
			
			/* slide toggle */
			$('#bu-slideshow-slidelist').on('click', '.bu-slide-expand', function() {
				var clicked = $(this), editor;
				editor = clicked.parents('.bu-slideshow-slide').find('.bu-slide-edit-container');
				
				if (clicked.hasClass('open')) {
					clicked.removeClass('open');
					editor.slideUp(300);
				} else {
					clicked.addClass('open');
					editor.slideDown(300);
				}
				
				return false;
			});
			
			/* replace slide title as user types */
			$('#bu-slideshow-slidelist').on('keyup', '.bu-slideshow-title-input', function() {
				var input = $(this);
				input.parents('.bu-slideshow-slide').find('.bu-slide-title').text(input.val());
			});
			
			/* don't allow saving until slide reindexing is complete */
			$('#bu-slideshow-editform').on('submit', function() {
				
				var name = $('#bu_slideshow_name').val().replace(' ', '');
				if (!name) {
					
					displayError(buSlideshowLocalAdmin.emptyNameError, $(this));
					return false;
				}
				
				return (!window.reindexingSlides);
			});
			
			$('#bu-slideshow-slidelist ul').sortable({
				stop: reindexSlides,
				placeholder: "sortable-placeholder",
				start: createPlaceholder
			});
			
			// Add new slide button
			$('#bu-slideshow-add-slide').on('click', function() {
				var order = slideEditor.find('#bu-slideshow-slidelist li').length;
				addSlide(order);
				return false;
			});
			
			// Delete slide button
			$('#bu-slideshow-slidelist').on('click', '.bu-slide-delete-button', function() {

				if (confirm(buSlideshowLocalAdmin.deleteConfirmSlide)) {
					$(this).parents().parent('.bu-slideshow-slide').remove();
					reindexSlides();
				}
					
				return false;
			});
			
			// Media upload management
			window.buUploaders = {
				
				/* are we using old school uploader or WP 3.5+ uploader? */
				newEditor : (window.wp && window.wp.media) ? true : false,
				
				init : function(button) {
					var $button = $(button);
					
					if (!$button.length) {
						throw new TypeError('No valid button identified.');
					}
					
					this.slide = $button.parents('.bu-slideshow-slide');
					this.populateFields();
				},
				
				populateFields : function() {
				
					this.addButton = this.slide.find('.bu-slideshow-add-img');
					this.removeButton = this.slide.find('.bu-slideshow-remove-img');
					this.imgIdField = this.slide.find('.bu-slideshow-img-id');
					this.imgSizeField = this.slide.find('.bu-slideshow-img-size');
					this.imgMeta = this.slide.find('.bu-slide-meta');
					this.thumbContainers = this.slide.find('.bu-slide-thumb, .bu-slide-header-thumb');
				},
				
				// trigger appropriate media upload UI/handling
				select : function() {
					if (this.newEditor) {
						this.newHandleImageSelect();
					} else {
						this.oldHandleImageSelect();
					}
				},
				
				// remove image
				remove : function() {
					
					this.thumbContainers.each(function(index, el) {
						$(el).find('img').remove();
					});
					
					this.imgIdField.val('');
					this.imgSizeField.val('');
					this.imgMeta.hide();
					this.removeButton.hide();
				},
				
				handleImgThumbResponse : function(response) {
					var thumb, $el;
					
					response = $.parseJSON(response);
					if (!response || response === '0') {
						displayError(buSlideshowLocalAdmin.thumbFailError, this.slide.find('.bu-slide-edit-container'), true);
					} else {
			
						this.thumbContainers.each(function(index, el) {
							$el = $(el);
							thumb = $el.find('img');
							if (thumb.length) {
								thumb.attr('src', response[0]);
							} else {
								$el.append('<img src="' + response[0] + '" alt="' + buSlideshowLocalAdmin.thumbAltText + '" />');
							}
						});
						
						this.removeButton.show();
					}
				},
				
				/**
				 * Media uploader for WP 3.5+
				 */
				newHandleImageSelect : function() {
					var that = this;
					
					if (typeof buUploadFrame !== 'object') {
						
						var oldBrowseContent = wp.media.view.MediaFrame.Select.prototype.browseContent;
						
						that.modifyWPSelectFrame();
					
						buUploadFrame = wp.media.frames.bu_slideshow_frame = wp.media({
							'multiple' : false,
							'title' : buSlideshowLocalAdmin.mediaUploadTitle,
							'button' : { 'text' : buSlideshowLocalAdmin.mediaUploadButton },
							'library' : {
								'type' : 'image'
							}
						});
						
						// restore original functionality in case other scripts on page use uploader
						wp.media.view.MediaFrame.Select.prototype.browseContent = oldBrowseContent;

						buUploadFrame.on('select', function() {
							var img, props, state, imgId;
							
							state = buUploadFrame.state();
							img = state.get('selection').first();
							props = state.display(img).attributes;
							imgId = img.toJSON().id;
							
							that.getImgThumb(imgId);
							that.setImageDetails(imgId, props.size);
							that.slide.find('.bu-slide-meta').hide();
						});
					}
					
					buUploadFrame.open();
				},
				
				/**
				 * Patches the Select media frame to add the attachment details in the sidebar.
				 */
				modifyWPSelectFrame : function() {
					
					wp.media.view.MediaFrame.Select.prototype.browseContent = function( content ) {
						var state = this.state(), selection = state.get('selection');

						this.$el.removeClass('hide-toolbar');

						content.view = new wp.media.view.AttachmentsBrowser({
							controller: this,
							collection: state.get('library'),
							model:      state,
							// sortable:   state.get('sortable'),
							// search:     state.get('searchable'),
							filters:    state.get('filterable'),
							display:    true,
							// dragInfo:   state.get('dragInfo'),
							selection:  selection,
							sortable:   true,
							search:     false,
							dragInfo:   true,

							AttachmentView: wp.media.view.Attachment.EditLibrary
						});
					};
					
				},
				
				getImgThumb : function(imgId) {
					var that = this, data;
					
					data = {
						"action": 'bu_get_slide_thumb',
						"image_id": imgId
					};
					$.post(ajaxurl, data, function(response) {
						that.handleImgThumbResponse(response);
					});
				},
				
				setImageDetails : function(id, size) {
					this.imgIdField.val(id);
					this.imgSizeField.val(size);
				}
				
			};
			
			$('#bu-slideshow-slidelist').on('click', '.bu-slideshow-add-img', function(e) {
				window.buUploaders.init(this);
				window.buUploaders.select();
				return false;
			});
			
			$('#bu-slideshow-slidelist').on('click', '.bu-slideshow-remove-img', function() {
				window.buUploaders.init(this);
				window.buUploaders.remove();
				return false;
			});
			
			$('#bu-slideshow-slidelist .bu-slideshow-add-img').each(function() {
				setModalHeight($(this));
			});
			
		}
		
		$("#bu_slideshow_show_nav").on("click",function(){
			$(".bu_slideshow_nav_style").toggle();
		});
		
	});
}(jQuery));