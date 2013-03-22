/* patching jQuery for sequence.js compatability with jQuery 1.4 */

(function($) {
	if (typeof $.fn.on !== 'function') {
		console.log('no jQuery on defined');
		$.fn.on = $.fn.bind;
	}
})(jQuery);