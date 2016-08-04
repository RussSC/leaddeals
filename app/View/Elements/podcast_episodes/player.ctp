<?php
if (!empty($podcastEpisode['PodcastEpisode']['libsyn_id'])):
	echo $this->element('podcast_episodes/libsyn_player', [
		'id' => $podcastEpisode['PodcastEpisode']['libsyn_id'],
	]);
else:
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
<?php endif; ?>