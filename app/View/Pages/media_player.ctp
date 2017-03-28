<?php
$this->layout = 'ajax';
$mainFrame = Router::url("/");
$mediaFrame = Router::url(['controller' => 'podcast_episodes', 'action' => 'player', 3]);

?>
<!DOCTYPE html>
<html>
	<head>
		<script
  		src="https://code.jquery.com/jquery-2.2.4.min.js"
  		integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  		crossorigin="anonymous"></script>

		<script type="text/javascript">
			// parent_on_message(e) will handle the reception of postMessages (a.k.a. cross-document messaging or XDM).
			function parent_on_message(e) {
				// You really should check origin for security reasons
				// https://developer.mozilla.org/en-US/docs/DOM/window.postMessage#Security_concerns
				//if (e.origin.search(/^http[s]?:\/\/.*\.localhost/) != -1
				//	&& !($.browser.msie && $.browser.version <= 7)) {
					var returned_pair = e.data.split('=');
					if (returned_pair.length != 2){
						return;
					}
					if (returned_pair[0] === 'message-for-parent') {
						window.history.pushState('obj', 'newtitle', returned_pair[1]);
					}else{
						console.log("Parent received invalid message");
					}

				//}
			}
			(function($) {
				$(document).ready(function() {
					// Setup XDM listener (except for IE < 8)
					// Connect the parent_on_message(e) handler function to the receive postMessage event
					if (window.addEventListener){
						window.addEventListener("message", parent_on_message, false);
					}else{
						window.attachEvent("onmessage", parent_on_message);
					}
				});
			})(jQuery);
		</script>
	</head>
	<frameset rows="*,40px">
	  <frame id="page-frame" src="<?php echo $mainFrame; ?>" />
	  <frame id="media-frame" src="<?php echo $mediaFrame; ?>" />
	</frameset>
	<body></body>
</html>