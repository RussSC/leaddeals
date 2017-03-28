<div class="media-list podcast-episode-media-list">
<?php foreach ($podcastEpisodes as $podcastEpisode): 
	if(!empty($podcastEpisode['PodcastEpisode'])) {
		$podcastEpisode = $podcastEpisode['PodcastEpisode'];
	}
	$url = Router::url([
		'controller' => 'podcast_episodes', 
		'action' => 'view', 
		'id' => $podcastEpisode['id'],
		'slug' => $podcastEpisode['slug'],
	], true);
	$class = 'media';
	if (
		!empty($layoutData['modelName']) && 
		$layoutData['modelName'] == 'PodcastEpisode' &&
		!empty($layoutData['result']) &&
		$layoutData['result']['PodcastEpisode']['id'] == $podcastEpisode['id']
	) {
		$class .= ' active';
	}
	?>
	<a href="<?php echo $url; ?>" class="<?php echo $class; ?>">
		<div class="media-body">
			<h4 class="media-title">
				<?php echo $this->Podcast->episodeNumber($podcastEpisode['episode_number']); ?>. 
				<?php echo $podcastEpisode['title']; ?>
			</h4>
			<small><?php echo $podcastEpisode['subtitle']; ?></small>
		</div>
	</a>
<?php endforeach; ?>
</div>