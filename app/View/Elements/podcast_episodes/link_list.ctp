<div class="media-list podcast-episode-media-list">
<?php foreach ($podcastEpisodes as $podcastEpisode): 
	$url = Router::url(['controller' => 'podcast_episodes', 'action' => 'view', $podcastEpisode['PodcastEpisode']['id']], true);
	$class = 'media';
	if (
		!empty($layoutData['modelName']) && 
		$layoutData['modelName'] == 'PodcastEpisode' &&
		!empty($layoutData['result']) &&
		$layoutData['result']['PodcastEpisode']['id'] == $podcastEpisode['PodcastEpisode']['id']
	) {
		$class .= ' active';
	}
	?>
	<a href="<?php echo $url; ?>" class="<?php echo $class; ?>">
		<div class="media-body">
			<h5 class="media-title">
				<?php echo $podcastEpisode['PodcastEpisode']['episode_number']; ?>. 
				<?php echo $podcastEpisode['PodcastEpisode']['title']; ?>
			</h5>
			<?php echo $podcastEpisode['PodcastEpisode']['subtitle']; ?>
		</div>
	</a>
<?php endforeach; ?>
</div>