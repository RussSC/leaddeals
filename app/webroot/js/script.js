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
			n = "0" + String(n);
		}
		return n;
	}

	$.fn.inputDate = function() {
		return this.each(function() {
			var $this = $(this),
				$formVals = $('<div>').appendTo($this),
				$inputs = {},
				dateKeys = ['month', 'day', 'year', 'hour', 'min', 'meridian'];

			for(var i in dateKeys) {
				var key = dateKeys[i],
					find = ':input[name*="[' + key + ']"]';
				$(find, $this).detach().appendTo($formVals);
				$inputs[key] = $(find, $formVals);
			}

			var $datepicker = $('<input/>', {
					name: 'test_name',
					type: 'text',
					'class': 'form-control input-date-date'
				}).appendTo($this);

			$formVals.css({
				position: 'absolute',
				opacity: 0,
				top: '-99999px',
				left: '-99999px',
			});
			$(':input', $formVals).attr('tabIndex', -1).prop('required', false);

			$datepicker
				.datepicker()
				.change(function() {
					var date = $(this).datepicker("getDate"),
						D = new Date(date),
						m = D.getMonth() + 1,
						d = D.getDate(),
						y = D.getFullYear(),
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

					var updateVals = {
						'month': 	zeroPad(m),
						'day': 		zeroPad(d),
						'year': 	y,
					};

					for (var i in updateVals) {
						var v = updateVals[i];
						$('option', $inputs[i]).each(function() {
							$(this).prop('selected', $(this).attr('value') == v);
						});
					}
		
					if ($inputs.hour.length) {
						$inputs.hour.val(zeroPad(H));
						$inputs.min.val(zeroPad(i));
						$inputs.meridian.val(A);
					}
				})
				.datepicker('setDate', $inputs.month.val() + "/" + $inputs.day.val() + "/" + $inputs.year.val());
		});
	};

	$(document).ready(function() {
		$('.input-date').inputDate();
	});
})(jQuery);