/* after we upgrade WP some enterprising person should come through and 
 * update the jQuery syntax too. */

jQuery(document).ready(function($){
	
	var newSlideshowForm = $('#bu-slideshow-newform'), slideShowList = $('#bu-slideshow-manage'),
		slidesContainer = $('#bu-slideshow-slides'), slideEditor = $('#bu-slideshow-edit'),
		manageUrl = 'admin.php?page=bu-slideshow';
	
	/** New slideshow page */
	if (newSlideshowForm.length) {
		newSlideshowForm.submit(function() {
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
			}
			
			that.addEmptyMsg = function() {
				that.getUrl('add_url', function(addUrl) {
					var html = '<li><p>No slideshows yet.</p>' +
						'<p><a class="button" href="' + addUrl + '">Add a slideshow</a></p></li>';
					that.list.append(html);
				});
				
			}
			
			that.getUrl = function(url, cb) {
				var data = {
					"action" : "bu_slideshow_get_url",
					"url" : url
				};
				$.post(ajaxurl, data, function(response) {
					cb(response);
				});
			}
			
			that.list.find('.bu-slideshow-delete').live('click', function() {
				var result = confirm('Are you sure you want to delete this slideshow? This action cannot be undone.');
				
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
						
					})
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
					displayError('Could not delete slideshow.', that.container);
					return false;
				}
			}
		}
		
		slideShowMgr({'list': '#bu-slideshow-manage'});
		
	}
	
	/* Insert slideshow button and Inserting shortcode into editor */
	if ($('#bu_slideshow_modal_button').length && typeof buModal === 'function') {
		var modal = buModal({ 'el' : '#bu_slideshow_modal_wrap' });
		
		$('#bu_slideshow_modal_button').click(function() {
			modal.open();
		});
		
		$('#bu_insert_slideshow').live('click', function(e) {
			var selector = slideshowSelector('#bu_slideshow_modal_wrap .bu-slideshow-selector'), options, html;
			selector.ui.parent().find('.error').remove();
			options = selector.getOptions();

			if (!parseInt(options.show_id)) {
				displayError('You must select a slideshow.', selector.ui.parent());
				return false;
			}

			html = '[bu_slideshow show_id="' + options.show_id + '" show_nav="' + options.show_nav + '" transition="' + options.transition + '" nav_style="' + options.nav_style + '" autoplay="' + options.autoplay + '"]';

			window.send_to_editor("<br />" + html + "<br />");
			selector.reset();
			modal.close();
			return false;
		});
	}
	
	/* Edit Slideshow page */
	if (slideEditor.length) {
		
		$('.bu-slide-edit-container').hide();
		
		$('.bu-slide-expand').live('click', function() {
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
		
		$('.bu-slideshow-title-input').live('keyup', function() {
			var input = $(this);
			input.parents('.bu-slideshow-slide').find('.bu-slide-title').text(input.val());
		})
		
		/* don't allow saving until slide reindexing is complete */
		$('#bu-slideshow-editform').live('submit', function() {
			
			var name = $('#bu_slideshow_name').val().replace(' ', '');
			if (!name) {
				
				displayError('The name field for the slideshow cannot be empty.', $(this));
				return false;
			}
			
			return (!window.reindexingSlides);
		});
		
		/* update slide input names so that new order is saved */
		function reindexSlides(event, ui) {
			$('#bu-slideshow-editform-submit').attr('disabled', 'disabled');
			window.reindexingSlides = true;
			
			var regEx = /bu_slides\[([0-9]*)\](.*)/;
			var name = '';
			
			$('.bu-slideshow-slide').each(function(index, el) {
				$(el).find('input, textarea').each(function(i, e) {
					var $e = $(e);
					name = $e.attr('name');
					name = name.replace(regEx, "bu_slides[" + index + "]$2");
					$e.attr('name', name);
				});
			});
			
			$('#bu-slideshow-editform-submit').removeAttr('disabled');
			window.reindexingSlides = false;
		}
		
		// Make slides sortable
		function createPlaceholder(event, ui) {
			var h = ui.item.height();
			var w = ui.item.width();
			$('.sortable-placeholder').outerHeight(h).width(w);
		}
		
		$('#bu-slideshow-slidelist ul').sortable({
			stop: reindexSlides,
			placeholder: "sortable-placeholder",
			start: createPlaceholder
		});
		
		// Add new slide button
		$('#bu-slideshow-add-slide').click(function() {
			var order = slideEditor.find('#bu-slideshow-slidelist li').length;
			addSlide(order);
			return false;
		});
		
		// Delete slide button
		$('.bu-slide-delete-button').live('click', function() {
			$(this).parents().parent('.bu-slideshow-slide').remove();
			reindexSlides();
				
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
				this.thumbContainers = this.slide.find('.bu-slide-thumb, .bu-slide-header-thumb');
			},
			
			// trigger appropriate media upload UI/handling
			select : function() {
				if (this.newEditor) {
					//return false;
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
				
				this.removeButton.hide();
			},
			
			oldHandleImageSelect : function() {
				
				var that = this;
				
				window.send_to_editor = function(html) {
					var imgClass, regex, r, imgId, imgSize, data, thumb;
					
					imgClass = $('img', html).attr('class');

					regex = /wp-image-([0-9]*)/i;
					r = regex.exec(imgClass);
					imgId = r[1]

					that.imgIdField.val(imgId);

					regex = /size-([a-zA-Z]*)/i;
					r = regex.exec(imgClass);
					imgSize = r[1];

					that.imgSizeField.val(imgSize);

					data = {
						"action": 'bu_get_slide_thumb',
						"image_id": imgId
					};
					$.post(ajaxurl, data, function(response) {
						that.handleImgThumbResponse(response);
					})

					tb_remove();
				}
			},
			
			handleImgThumbResponse : function(response) {
				var thumb;
				
				if (!response || response === '0') {
					displayError('Could not load image thumbnail.', this.slide);
				} else {
					response = $.parseJSON(response);

					this.thumbContainers.each(function(index, el) {
						thumb = $(el).find('img');
						if (thumb.length) {
							thumb.attr('src', response[0]);
						} else {
							$(el).append('<img src="' + response[0] + '" alt="thumbnail for this slide\'s image" />');
						}
					});
					
					this.removeButton.show();
				}
			}
			
		}
		
		$('.bu-slideshow-add-img').live('click', function(e) {
			e.preventDefault();
			window.buUploaders.init(this);
			window.buUploaders.select();
			return false;
		});
		
		$('.bu-slideshow-remove-img').live('click', function() {
			window.buUploaders.init(this);
			window.buUploaders.remove();
			return false;
		});
		
		function addSlide(order) {
			var data = {
				"action": "bu_add_slide",
				"order": order
			};
			
			$.post(ajaxurl, data, function(response) {
				if (!response) {
					displayError('Could not create new slide.', $('#bu-slideshow-slidelist'));
					return;
				} else {
					var r = $(response);
					r.find('.bu-slide-edit-container').css('display', 'none');
					r.appendTo('#bu-slideshow-slidelist ul');
				}
			});
		}
	}
	
	/**
	 * Removes any existing errors and displays new one.
	 */
	function displayError(msg, target) {
		$('.error').remove();
		var html = '<div class="error"><p>' + msg + '</p></div>';
		
		target.append(html);
		
	}
	
});