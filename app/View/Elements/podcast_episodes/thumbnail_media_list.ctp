<div class="media-list podcast-episode-media-list">
<?php foreach ($result as $podcastEpisode): 
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
		<?php echo $this->FieldUploadImage->image(
			$podcastEpisode['Podcast'], 'thumbnail', 'thumbnail', 
			['class' => 'pull-left media-object']
		); ?>
		<div class="media-body">
			<h5 class="media-title">
				<?php echo $podcastEpisode['Podcast']['title']; ?>
				<small><?php echo $podcastEpisode['PodcastEpisode']['numeric_title']; ?></small>
			</h5>
			<?php echo $podcastEpisode['PodcastEpisode']['subtitle']; ?>
		</div>
	</a>
<?php endforeach; ?>
</div>