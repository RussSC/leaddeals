<?php 
$playerId = 'popup-jplayer';
echo $this->element('podcast_episodes/player', compact('playerId')); 

$this->Html->scriptStart(['inline' => false]); ?>
	$(document).ready(function() {
		// http://jplayer.org/latest/developer-guide/#jPlayer-events
		$("#<?php echo $playerId; ?>").bind($.jPlayer.event.loadeddata, function(event) { // Add a listener to report the time play began
  			console.log("LOADED");
  			$('#<?php echo $playerId; ?>').jPlayer("play");
		});
	});
<?php $this->Html->scriptEnd(); ?>