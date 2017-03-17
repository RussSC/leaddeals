// Detect if this page is loaded inside an Iframe window
function inIframe() {
	try {
		console.log(document.location.href);
		console.log(window.top.getAttribute("id"));
		console.log(parent.getAttribute("id"));

		return window.self !== window.top;
	} catch (e) {
		return true;
	}
}

function sendUrlToParent(url) {
	parent.window.postMessage('message-for-parent=' + url, '*');
}
(function($) {
	$(document).ready(function() {
		if(inIframe()) {
			console.log("IN FRAME");
			// click even on links that are clicked without the CTRL key pressed
			$('a').on('click', function(e) {
				e.preventDefault();

				console.log("CLICKED");

				// is this link local on the same domain as this page is?
				if( window.location.hostname === this.hostname ) {
					// new URL with ?sidebar=no appended to the URL of local links that are clicked on inside of an iframe
					var linkUrl = $(this).attr('href');
					var noSidebarUrl = $(this).attr('href')+'?sidebar=no';

					// send URL to parent window
					sendUrlToParent(linkUrl);

					// load Iframe with clicked on URL content
					document.location.href = linkUrl;
					return false;
				}
			});
			sendUrlToParent(document.location.href);
		}
	});
})(jQuery);

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