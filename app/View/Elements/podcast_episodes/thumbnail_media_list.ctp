<div class="media-list podcast-episode-media-list">
<?php foreach ($result as $row): 
	$podcastEpisode = $row['PodcastEpisode'];
	$url = Router::url(['controller' => 'podcast_episodes', 'action' => 'view', $podcastEpisode['id']], true);

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
		<?php echo $this->FieldUploadImage->image(
			$row['Podcast'], 'thumbnail', 'thumbnail', 
			['class' => 'pull-left media-object']
		); ?>
		<div class="media-body">
			<h5 class="media-title">
				<?php echo $row['Podcast']['title']; ?>
				<small><?php echo $podcastEpisode['numeric_title']; ?></small>
			</h5>
			<?php echo $this->Text->truncate($podcastEpisode['description'], 235, [
				'ending' => '...',
				'exact' => true,
				'html' => true,
			]);
		?>
		</div>
	</a>
<?php endforeach; ?>
</div>