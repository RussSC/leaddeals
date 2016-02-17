<?php
if (empty($playerId)) {
	$playerId = 'podcast-episode-' . $podcastEpisode['PodcastEpisode']['id'];
}
?>
<div class="podcast-episode-view-player">
	<?php echo $this->element('jplayer/player', [
		'title' => $podcastEpisode['PodcastEpisode']['full_title'],
		'file' => $podcastEpisode['PodcastEpisode']['download_url'],
		'playerId' => $playerId,
	]); ?>
</div>
