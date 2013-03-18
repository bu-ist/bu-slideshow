/* after we upgrade WP some enterprising person should come through and 
 * update the jQuery syntax too. */

jQuery(document).ready(function($){
	
	var newSlideshowForm = $('#bu-slideshow-newform'), slideShowTable = $('#bu-slideshow-manage'),
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
	
	/** Manage slideshows page 
	 */
	if (slideShowTable.length && slidesContainer.length) {
		$('.bu-slideshow-delete').live('click', function() {
			var result = confirm('Are you sure you want to delete this slideshow? This action cannot be undone.');
			if (result) {
				var showId = $(this).attr('data-slideshow-id'), that = $(this), nonce = $('#bu_slideshow_nonce').val();
					
				var data = {
					"action": 'bu_delete_slideshow',
					"id": showId,
					"bu_slideshow_nonce": nonce
				};
				$.post(ajaxurl, data, function(response) {
					if (response && response !== '0') {
						that.parent().parent('li').remove();
						slidesContainer.find('.error').remove();
					} else {
						displayError('Could not delete slideshow.', slidesContainer);
						return false;
					}
				})
			}

			return false;
		});
		
	}
	
	/* Insert slideshow button and Inserting shortcode into editor */
	if ($('#bu_slideshow_modal_button').length) {
		var modal = buModal('#bu_slideshow_modal_wrap');
		
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

			console.log(html);
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
			
			var name = $('#bu_slideshow_name').val();
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
					name = $(e).attr('name');
					name = name.replace(regEx, "bu_slides[" + index + "]$2");
					$(e).attr('name', name);
				});
			});
			
			$('#bu-slideshow-editform-submit').removeAttr('disabled');
			window.reindexingSlides = false;
		}
		
		function createPlaceholder(event, ui) {
			var h = ui.item.height();
			var w = ui.item.width();
			$('.sortable-placeholder').outerHeight(h).width(w);
		}
		
		$('.bu-slideshow-slidelist ul').sortable({
			stop: reindexSlides,
			placeholder: "sortable-placeholder",
			start: createPlaceholder
		});
		
		$('#bu-slideshow-add-slide').click(function() {
			var order = slideEditor.find('.bu-slideshow-slidelist li').length;
			addSlide(order);
			return false;
		});
		
		$('.bu-slide-delete-button').live('click', function() {
			$(this).parents().parent('.bu-slideshow-slide').remove();
			reindexSlides();
				
			return false;
		});
		
		$('.bu-slideshow-add-img').live('click', function() {
			window.imgDestination = $(this).parent('.bu-slide-thumb-container');
		});
		
		window.send_to_editor = function(html) {
			console.log(html);
			var imgClass = $('img', html).attr('class');
			
			var regex = /wp-image-([0-9]*)/i;
			var r = regex.exec(imgClass);
			var imgId = r[1]
			
			regex = /size-([a-zA-Z]*)/i;
			r = regex.exec(imgClass);
			var imgSize = r[1];
			
			if (window.imgDestination.length) {
				window.imgDestination.find('.bu-slideshow-img-id').val(imgId)
					.end().find('.bu-slideshow-img-size').val(imgSize);
			}
			
			var data = {
				"action": 'bu_get_slide_thumb',
				"image_id": imgId
			};
			$.post(ajaxurl, data, function(response) {
				if (!response || response === '0') {
					if (window.imgDestination.length) {
						var target = window.imgDestination.parents('.bu-slideshow-slide');
						displayError('Could not load image thumbnail.', target);
					}
				} else {
					response = $.parseJSON(response);
					if (window.imgDestination.length) {
						var slide = window.imgDestination.parents('.bu-slideshow-slide');
						
						var thumbContainers = slide.find('.bu-slide-thumb, .bu-slide-header-thumb');
						thumbContainers.each(function(index, el) {
							var thumb = $(el).find('img');
							if (thumb.length) {
								thumb.attr('src', response[0]);
							} else {
								$(el).append('<img src="' + response[0] + '" alt="thumbnail for this slide\'s image" />');
							}
						});
						
					}
				}
			})
			
			tb_remove();
		}
		
		function addSlide(order) {
			var data = {
				"action": "bu_add_slide",
				"order": order
			};
			
			$.post(ajaxurl, data, function(response) {
				if (!response) {
					displayError('Could not create new slide.', $('.bu-slideshow-slidelist'));
					return;
				} else {
					var r = $(response);
					r.find('.bu-slide-edit-container').css('display', 'none');
					r.appendTo('.bu-slideshow-slidelist ul');
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