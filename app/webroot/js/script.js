(function($) {
	$.fn.podcastPlayer = function() {
		var windowHeight = 200,
			windowWidth = 600,
			windowTitle = 'Podcast Player';

		return this.each(function() {
			var $this = $(this);
			$this.click(function(e) {
				window.open($this.attr('href'), windowTitle, 'height=' + windowHeight + ',width=' + windowWidth);
				e.preventDefault();
			});
		});
	};

	$(document).ready(function() {
		$('.podcast-player').podcastPlayer();
	});
})(jQuery);