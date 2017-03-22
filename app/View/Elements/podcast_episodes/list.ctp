<?php
$thumbField = 'thumbnail';
$thumbSize = 'thumbnail-lg';
$thumbOptions = ['modified' => true];
?>
<div class="podcast-episode-list">
<?php foreach ($podcastEpisodes as $podcastEpisode): 
	$url = [
		'controller' => 'podcast_episodes', 
		'action' => 'view', 
		'id' => $podcastEpisode['PodcastEpisode']['id'],
		'slug' => $podcastEpisode['PodcastEpisode']['slug'],
	];

	$number = $podcastEpisode['PodcastEpisode']['episode_number'];
	if ($number == round($number)) {
		$number = round($number);
	}
	$title = $podcastEpisode['PodcastEpisode']['title'];
	$text = $this->Text->truncate($podcastEpisode['PodcastEpisode']['description'], 235, [
		'ending' => '...',
		'exact' => true,
		'html' => true,
	]);

	$thumbnail = $this->FieldUploadImage->image($podcastEpisode['PodcastEpisode'], $thumbField, $thumbSize, $thumbOptions);
	if (!$thumbnail && !empty($podcastEpisode['Podcast'])) {
		$thumbnail = $this->FieldUploadImage->image($podcastEpisode['Podcast'], $thumbField, $thumbSize, $thumbOptions);
	}

	?><div class="podcast-episode-list-item">
		<div class="podcast-episode-list-item-inner">
			<div class="podcast-episode-list-item-banner">
				<?php echo $thumbnail; ?>
			</div>
			<a class="podcast-episode-list-item-link" href="<?php echo Router::url($url, true); ?>">
				<h4 class="podcast-episode-list-item-link-title">
					<small>Episode <?php echo $number; ?></small><br/>
					<?php echo $title; ?>
				</h4>
			</a>
			<footer>
				<?php echo $this->Html->link(
					'<i class="fa fa-play"></i><span class="sr-only">Play</span>',
					['action' => 'player'] + $url,
					[
						'escape' => false, 
						'class' => 'btn btn-default podcast-player',
						'title' => 'Play episode',
					]
				); ?>
				<?php echo $this->Html->link(
					'<i class="fa fa-download"></i><span class="sr-only">Download</span>',
					['action' => 'download'] + $url,
					[
						'escape' => false, 
						'class' => 'btn btn-default',
						'title' => 'Download episode',
					]
				); ?>
			</footer>
		</div>
	</div><?php endforeach; ?>
</div>