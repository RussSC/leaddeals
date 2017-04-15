<?php
$this->Html->css('views/podcast-episode-view', null, ['inline' => false]);

$podcastUrl = ['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcastEpisode['Podcast']['slug']];
$this->set('defaultCrumbs', false);
$this->Html->addCrumb('Podcast', ['controller' => 'podcasts', 'action' => 'index']);
$this->Html->addCrumb($podcastEpisode['Podcast']['title'], $podcastUrl);
$this->Html->addCrumb($podcastEpisode['PodcastEpisode']['title']);

if ($isEditor) {
	//$bannerImage = $this->FieldUploadImage->resizeLink($bannerImage, 'PodcastEpisode', $podcastEpisode['PodcastEpisode']['id'], 'banner', 'banner', [
	//	'escape' => false,
	//]);
}


$pagerNav = [
	'prev' => ['class' => 'previous', 'title' => 'Prev'], 
	'next' => ['class' => 'next', 'title' => 'Next']
];

$mainButtons = [
	[
		'title' => 'Download',
		'icon' => Icon::download(),
		'url' => ['action' => 'download', $podcastEpisode['PodcastEpisode']['id']],
		'urlTitle' => 'Download the episode',
	], [
		'title' => 'Subscribe',
		'icon' => Icon::rss(),
		'url' => ['controller' => 'podcasts', 'action' => 'feed', 'slug' => $podcastEpisode['Podcast']['slug']],
		'urlTitle' => 'Subscribe to the RSS Feed',
	],
];

if (!empty($podcastEpisode['Podcast']['itunes_url'])) {
	$mainButtons[] = [
		'title' => 'iTunes',
		'icon' => Icon::apple(),
		'url' => $this->Podcast->iTunesUrl($podcastEpisode['Podcast']['itunes_url']),
		'options' => [
			'title' => 'Subscribe in iTunes',
			'target' => '_blank',
		]
	];
}

?>

<div class="podcast-view-bg-wrap">
	<?php if (empty($podcastEpisode['PodcastEpisode']['active'])): ?>
		<div class="alert alert-warning">
			<h2 class="alert-title">Episode is Inactive</h2>
			This podcast episode is listed as inactive, and can only be viewed by admins until it's activated.
		</div>
	<?php endif; ?>

	<div class="podcast-episode-view">
		<?php echo $this->FieldUploadImage->image($podcastEpisode['PodcastEpisode'], 'banner', 'banner', [
			'class' => 'podcast-episode-view-banner',
			'modified' => true,
		]); ?>
		<article>
			<header>
				<h4 class="podcast-episode-view-header-podcast-title">
					<a href="<?php echo Router::url($podcastUrl); ?>" ><?php echo $podcastEpisode['Podcast']['title']; ?></a>
					 : Episode #<?php echo $this->Podcast->episodeNumber(
					 	$podcastEpisode['PodcastEpisode']['episode_number']
					 ); ?>
				</h4>
				<h2 class="podcast-episode-view-header-title">
					<?php echo $podcastEpisode['PodcastEpisode']['title']; ?>
				</h2>
			</header>
			<section>
				<div class="btn-group-actions">
					<?php foreach ($mainButtons as $btn):
						$options = ['escape' => false, 'class' => 'btn btn-info btn-lg'];
						if (!empty($btn['options'])) {
							$options += $btn['options'];
						}
						if (!empty($btn['urlTitle'])) {
							$options['title'] = $btn['urlTitle'];
						}
						echo $this->Html->link(
							$this->Html->tag('span', $btn['icon'], ['class' => 'btn-icon']) . $btn['title'],
							$btn['url'],
							$options
						) . ' ';
					endforeach; ?>
				</div>
				<?php echo $this->element('podcast_episodes/player'); ?>

				<div class="podcast-episode-view-body podcast-description">
					<?php if (!empty($podcastEpisode['PodcastEpisode']['description'])): ?>
						<?php echo nl2br($podcastEpisode['PodcastEpisode']['description']); ?>		
					<?php endif; ?>
					<h5 class="podcast-episode-view-date"><?php 
						echo date('F j, Y', strtotime($podcastEpisode['PodcastEpisode']['published'])); 
					?></h5>

					<?php if (!empty($podcastEpisode['User'])): ?>
						<?= $this->element('users/list', [
							'title' => 'In this episode', 
							'users' => $podcastEpisode['User']
						]) ?>
					<?php endif; ?>
				</div>


			</section>

			<?php if (!empty($podcastEpisode['Podcast']['PodcastLink'])):
				echo $this->element('podcast_links/list', [
					'podcastLinks' => $podcastEpisode['Podcast']['PodcastLink'],
				]);
			endif; ?>

			<footer>
				<div class="pager">
					<?php foreach ($pagerNav as $key => $config):
						$class = $config['class'];
						if (!empty($neighbors[$key])) {
							$neighbor = $neighbors[$key]['PodcastEpisode'];
							$url = ['action' => 'view', $neighbor['id']];
							$title = '<small>Episode ' . $this->Podcast->episodeNumber(
								$neighbor['episode_number']) . '</small>';
							$title .= '<br/>';
							$title .= $neighbor['title'];
						} else {
							$url = '#';
							$class .= ' disabled';
							$title = '&nbsp;';
						}
						echo $this->Html->tag('li',
							$this->Html->link($title, $url, ['escape' => false]),
							compact('class')
						);
					endforeach; ?>
				</div>
			</footer>
		</article>
	</div>
	<?php if (!empty($isEditor)): 
		echo $this->element('editor_panel', [
			'actions' => ['edit', 'delete' => ['prefix' => 'admin']],
		]);
	endif ?>

	<?php echo $this->element('podcast_episodes/list'); ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Recent Episodes</div>
		</div>
		<?php echo $this->element('podcast_episodes/thumbnail_media_list', [
			'result' => $recentEpisodes,
		]); ?>
	</div>

</div>

<div class="podcast-view-bg">
	<?php echo $this->FieldUploadImage->image($podcastEpisode['Podcast'], 'banner', 'banner', ['modified' => true]); ?>
</div>