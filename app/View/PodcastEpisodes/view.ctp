<?php
$this->Html->css('views/podcast-episode-view', null, ['inline' => false]);
$podcastUrl = ['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcastEpisode['Podcast']['slug']];
$pagerNav = [
	'prev' => ['class' => 'previous', 'title' => '<i class="fa fa-arrow-left"></i> Prev'], 
	'next' => ['class' => 'next', 'title' => 'Next <i class="fa fa-arrow-right"></i>']
];
?>

<?php if (empty($podcastEpisode['PodcastEpisode']['active'])): ?>
	<div class="alert alert-danger lead">
		<h2 class="alert-title">Episode is Inactive</h2>
		This podcast episode is listed as inactive, and can only be viewed by admins until it's activated.
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a class="media podcast-episode-podcast-heading" href="<?php echo Router::url($podcastUrl); ?>" >
					<?php echo $this->FieldUploadImage->image($podcastEpisode['Podcast'], 'thumbnail', 'thumbnail-sm', ['class' => 'pull-left media-object']); ?>
					<div class="media-body">
						<h3 class="podcast-episode-podcast-heading-title"><?php echo $podcastEpisode['Podcast']['title']; ?></h3>
					</div>
				</a>
			</div>
			<div class="panel-body">
				<div class="podcast-episode-view">
					<div class="podcast-episode-view-heading">
						<?php 
						$image = $this->FieldUploadImage->image($podcastEpisode['PodcastEpisode'], 'banner', 'banner', [
							'class' => 'podcast-episode-view-heading-banner',
						]); 
						if ($isEditor) {
							$image = $this->FieldUploadImage->resizeLink($image, 'PodcastEpisode', $podcastEpisode['PodcastEpisode']['id'], 'banner', 'banner', [
								'escape' => false,
							]);
						}
						echo $image;
						?>
						<h2 class="podcast-episode-view-title">
							<div class="pull-right">
								<?php echo $this->Html->link(
									'<i class="fa fa-download"></i> DOWNLOAD',
									['action' => 'download', $podcastEpisode['PodcastEpisode']['id']],
									['escape' => false, 'class' => 'btn btn-default', 'title' => 'Dowload the Episode']
								); ?>
								<?php echo $this->Html->link(
									'<i class="fa fa-rss"></i> FEED',
									['controller' => 'podcasts', 'action' => 'feed', 'slug' => $podcastEpisode['Podcast']['slug']],
									['escape' => false, 'class' => 'btn btn-default', 'title' => 'RSS Feed']
								); ?>

							</div>
							<?php echo $podcastEpisode['PodcastEpisode']['episode_number']; ?>. 
							<?php echo $podcastEpisode['PodcastEpisode']['title']; ?>
							<br/>
							<small>Posted on: <?php echo date('F j, Y', strtotime($podcastEpisode['PodcastEpisode']['posted'])); ?></small>
						</h2>

					</div>

					<?php echo $this->element('podcast_episodes/player'); ?>

					<div class="podcast-episode-view-body">
						<?php echo nl2br($podcastEpisode['PodcastEpisode']['description']); ?>		
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="pager">
					<?php foreach ($pagerNav as $key => $config):
						$class = $config['class'];
						if (!empty($neighbors[$key])) {
							$url = ['action' => 'view', $neighbors[$key]['PodcastEpisode']['id']];
						} else {
							$url = '#';
							$class .= ' disabled';
						}
						echo $this->Html->tag('li',
							$this->Html->link($config['title'], $url, ['escape' => false]),
							compact('class')
						);
					endforeach; ?>
				</div>
			</div>
		</div>
		<?php if (!empty($isEditor)): 
			echo $this->element('editor_panel', [
				'actions' => ['edit', 'delete' => ['prefix' => 'admin']],
			]);
		endif ?>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Recent Episodes</div>
			</div>
			<?php echo $this->element('podcast_episodes/thumbnail_media_list', [
				'result' => $recentEpisodes,
			]); ?>
		</div>
	</div>
</div>