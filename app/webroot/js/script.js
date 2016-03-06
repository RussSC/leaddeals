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


(function($) {
	function zeroPad(n) {
		if (n < 0) {
			n = "0" + n;
		}
		return n;
	}

	$.fn.inputDate = function() {
		return this.each(function() {
			var $this = $(this),
				$month = $(':input[name*="[month]"]', $this).hide(),
				$day = $(':input[name*="[day]"]', $this).hide(),
				$year = $(':input[name*="[year]"]', $this).hide(),
				$hour = $(':input[name*="[hour]"]', $this).hide(),
				$min = $(':input[name*="[min]"]', $this).hide(),
				$meridian = $(':input[name*="[meridian]"]', $this).hide(),
				$datepicker = $('<input/>', {
					name: 'test_name',
					type: 'text',
					'class': 'form-control input-date-date'
				}).appendTo($this);
			$datepicker
				.datepicker()
				.change(function() {
					var date = $(this).datepicker("getDate"),
						D = new Date(date),
						m = D.getMonth() + 1,
						d = D.getDay() + 1,
						y = D.getYear(),
						H = D.getHours(),
						i = D.getMinutes(),
						A = 'am';
					if (H >= 12) {
						A = 'pm';
					}
					if (H > 12) {
						H -= 12;
					} else if (H == 0) {
						H = 12;
					}
					$month.val(zeroPad(m));
					$day.val(zeroPad(d));
					$year.val(zeroPad(y));
					if ($hour.length) {
						$hour.val(zeroPad(H));
						$min.val(zeroPad(i));
						$meridian.val(A);
					}
				})
				.datepicker('setDate', $month.val() + "/" + $day.val() + "/" + $year.val());
		});
	};

	$(document).ready(function() {
		$('.input-date').inputDate();
	});
})(jQuery);