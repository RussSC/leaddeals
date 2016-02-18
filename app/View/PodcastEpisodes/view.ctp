<?php
$this->Html->css('views/podcast-episode-view', null, ['inline' => false]);
$podcastUrl = ['controller' => 'podcasts', 'action' => 'view', $podcastEpisode['Podcast']['id']];
$pagerNav = [
	'prev' => ['class' => 'previous', 'title' => '<i class="fa fa-arrow-left"></i> Prev'], 
	'next' => ['class' => 'next', 'title' => 'Next <i class="fa fa-arrow-right"></i>']
];
?>

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
						<h2 class="podcast-episode-view-title">
							<div class="pull-right">
								<?php echo $this->Html->link(
									'<i class="fa fa-download"></i> DOWNLOAD',
									['action' => 'download', $podcastEpisode['PodcastEpisode']['id']],
									['escape' => false, 'class' => 'btn btn-default', 'title' => 'Dowload the Episode']
								); ?>
								<?php echo $this->Html->link(
									'<i class="fa fa-rss"></i> FEED',
									['controller' => 'podcasts', 'action' => 'feed', $podcastEpisode['PodcastEpisode']['podcast_id']],
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
				'actions' => ['edit', 'delete'],
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