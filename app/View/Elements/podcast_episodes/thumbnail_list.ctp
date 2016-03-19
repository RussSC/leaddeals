<div class="thumbnail-list thumbnail-list-sm podcast-episode-thumbnail-list">
	<?php foreach ($podcastEpisodes as $row): 
		$podcastEpisode = !empty($row['PodcastEpisode']) ? $row['PodcastEpisode'] : $row;
		$url = Router::url([
			'controller' => 'podcast_episodes', 
			'action' => 'view', 
			'id' => $podcastEpisode['id'],
			'slug' => $podcastEpisode['slug'],
		]);
		?>
		<a href="<?php echo $url; ?>" class="thumbnail-list-item">
			<?php echo $this->Podcast->episodeImage($row, 'thumbnail', 'thumbnail-lg', ['class' => 'thumbnail-list-item-img']); ?>
			<div class="thumbnail-list-item-caption">
				<h2 class="thumbnail-list-item-title"><?php echo $this->Podcast->episodeTitle($row); ?></h2>
				<?php echo $podcastEpisode['subtitle']; ?>
			</div>
		</a>
	<?php endforeach; ?>
</div>