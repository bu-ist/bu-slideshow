/* patching jQuery for sequence.js compatability with jQuery 1.4 */

(function($) {
	if (typeof $.on !== 'function') {
		$.fn.on = $.fn.bind;
	}
})(jQuery);