<div class="media-list podcast-episode-media-list">
<?php foreach ($podcastEpisodes as $podcastEpisode): 
	$url = [
		'controller' => 'podcast_episodes', 
		'action' => 'view', 
		'id' => $podcastEpisode['PodcastEpisode']['id'],
		'slug' => $podcastEpisode['PodcastEpisode']['slug'],
	];
	?>
	<div class="media">
		<div class="pull-right">
			<?php echo $this->Html->link(
				'<i class="fa fa-play"></i><span class="sr-only">Play</span>',
				['action' => 'player'] + $url,
				['escape' => false, 'class' => 'btn btn-default podcast-player']
			); ?>
			<?php echo $this->Html->link(
				'<i class="fa fa-download"></i><span class="sr-only">Download</span>',
				['action' => 'download'] + $url,
				['escape' => false, 'class' => 'btn btn-default']
			); ?>
		</div>
		<div class="media-body">
			<h4 class="media-title">
				<?php echo $this->Html->link($podcastEpisode['PodcastEpisode']['numeric_title'], $url); ?>
			</h4>
			<?php echo $podcastEpisode['PodcastEpisode']['subtitle']; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>