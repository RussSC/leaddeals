<?php 
// Directories
$dir = '/jPlayer-2.9.2/';
$distDir = $dir . 'dist/';
$libDir = $dir . 'lib/';
$jPlayerDir = $distDir . 'jplayer/';

if (empty($playerId)) {
	$playerId = 'jquery_jplayer_1';
}

//$this->Html->css($distDir . 'skin/blue.monday/css/jplayer.blue.monday.min', null, ['inline' => false]);
$this->Html->script([
	$libDir . 'jquery.min',
	$jPlayerDir . 'jquery.jplayer.min',
], ['inline' => false]);

$files = [
	'mp3' => $file,
];

$this->Html->scriptStart(['inline' => false]); ?>
$(document).ready(function(){
	$("#<?php echo $playerId;?>").jPlayer({
		ready: function (event) {
			$(this).jPlayer("setMedia", {
				title: "<?php echo addslashes($title); ?>",
				mp3: "<?php echo $file; ?>"
			});
		},
		swfPath: "<?php echo Router::url($jPlayerDir, true); ?>",
		supplied: "<?php echo implode(', ', array_keys($files)); ?>",
		wmode: "window",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		remainingDuration: true,
		toggleDuration: true
	});
});
<?php $this->Html->scriptEnd(); ?>

<div id="<?php echo $playerId;?>" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio panel panel-default" role="application" aria-label="media player">
	<div class="jp-type-single">
		<div class="jp-gui jp-interface">
			<div class="jp-controls">
				<button class="jp-play btn btn-primary" role="button" tabindex="0">
					<i class="fa fa-play"></i><span class="sr-only">Play</span>
				</button>
				<button class="jp-stop btn btn-default btn-sm" role="button" tabindex="0">
					<i class="fa fa-stop"></i><span class="sr-only">Stop</span>
				</button>
			</div>
			
			<div class="jp-volume-controls">
				<button class="jp-mute btn btn-default btn-sm" role="button" tabindex="0">
					<i class="fa fa-volume-off"></i><span class="sr-only">Mute</span>
				</button>
				<button class="jp-volume-max btn btn-default btn-sm" role="button" tabindex="0">
					<i class="fa fa-volume-up"></i><span class="sr-only">Volume Max</span>
				</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>

			<div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-toggles">
					<button class="jp-repeat btn btn-default btn-sm" role="button" tabindex="0">
						<i class="fa fa-repeat"></i><span class="sr-only">Repeat</span>
					</button>
				</div>
			</div>

			<div class="jp-progress">
				<div class="jp-seek-bar progress">
					<div class="jp-play-bar progress-bar"></div>
				</div>
			</div>

		</div>
		<div class="jp-details">
			<div class="jp-title" aria-label="title">&nbsp;</div>
		</div>
		<div class="jp-no-solution alert alert-danger">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>