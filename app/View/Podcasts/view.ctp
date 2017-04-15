<?php
$mainButtons = [
	[
		'title' => 'Subscribe',
		'icon' => Icon::rss(),
		'url' => ['controller' => 'podcasts', 'action' => 'feed', 'slug' => $podcast['Podcast']['slug']],
		'urlTitle' => 'RSS Feed',
	]
];
if (!empty($podcast['Podcast']['itunes_url'])):
	$mainButtons[] = [
		'title' => 'iTunes',
		'icon' => Icon::apple(),
		'url' => $this->Podcast->iTunesUrl($podcast['Podcast']['itunes_url']),
		'urlTitle' => 'Subscribe via iTunes',
	];
endif;

if (!empty($podcast['Podcast']['store_url'])):
	$mainButtons[] = [
		'title' => 'Store',
		'icon' => Icon::shoppingCart(),
		'url' => $podcast['Podcast']['store_url'],
		'urlTitle' => 'Shop products',
	];
endif;

?>

<?php $this->Html->css('views/podcast-view', null, ['inline' => false]); ?>

<?php if (empty($podcast['Podcast']['active'])): ?>
	<div class="alert alert-warning">
		<h2 class="alert-title">Podcast is Inactive</h2>
		This podcast is listed as inactive, and can only be viewed by admins until it's activated.
	</div>
<?php endif; ?>

<div class="podcast-view-bg">
	<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'banner', 'banner', ['modified' => true]); ?>
</div>


<div class="podcast-view">
	<div class="panel panel-default">
		<div class="panel-body">
			<header class="podcast-view-heading">
				<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'thumbnail-md', [
					'class' => 'podcast-view-thumbnail',
					'modified' => true,
				]); ?>
				<h1 class="podcast-view-title">
					<?php echo $podcast['Podcast']['title']; ?>
				</h1>

				<div class="btn-group-actions">
					<?php foreach ($mainButtons as $config):
						echo $this->Html->link(
							$this->Html->tag('span', $config['icon'], ['class' => 'btn-icon']) . $config['title'],
							$config['url'],	[
								'escape' => false, 
								'title' => $config['urlTitle'], 
								'target' => '_blank'
							]
						); 
						echo ' ';
					endforeach; ?>
				</div>
				<?php if (!empty($podcast['Podcast']['subtitle'])): ?>
					<h3 class="podcast-view-subtitle">
						<?php echo $podcast['Podcast']['subtitle']; ?>
					</h3>
				<?php endif; ?>
			</header>
			<section class="podcast-view-body">
				<?php if (!empty($podcast['Podcast']['description'])): ?>
					<div class="podcast-view-description podcast-description">
						<?php echo $this->DisplayText->text($podcast['Podcast']['description']); ?>
					</div>
				<?php endif; ?>
			</section>

			<?php if (!empty($articles)): ?>
				<section class="podcast-view-articles">
					<?php echo $this->element('articles/small_list', compact('articles')); ?>
					<?php echo $this->Html->link(
						'View more',
						['controller' => 'articles', 'action' => 'index', 'ResultFilter-podcast_id' => $podcast['Podcast']['id']]
					); ?>
				</section>
			<?php endif; ?>

			<?php if (!empty($recentPodcastEpisode)): ?>
				<section class="podcast-view-body">
					<h5>Latest Episode: <?php echo $this->Html->link(
						$recentPodcastEpisode['PodcastEpisode']['numeric_title'], [
							'controller' => 'podcast_episodes', 
							'action' => 'view', 
							$recentPodcastEpisode['PodcastEpisode']['id']
						]
					); ?></h5>
					<?php echo $this->element('podcast_episodes/player', [
						'podcastEpisode' => $recentPodcastEpisode,
					]); ?>
				</section>
			<?php endif; ?>

			<footer>
				<?php if (!empty($podcast['User'])): ?>
					<?= $this->element('users/list', ['title' => 'Authors', 'users' => $podcast['User']]) ?>
				<?php endif; ?>

				<?php if (!empty($podcast['PodcastLink'])):
					echo $this->element('podcast_links/list', [
						'podcastLinks' => $podcast['PodcastLink'],
					]);
				endif; ?>
			</footer>
		</div>
	</div>

	
	<?php if (!empty($isEditor)): 
		if ($this->Session->read('Auth.User.is_admin')) {
			//$prefix = 'admin';
			$actions = [
			//	'view', 
				'edit', 
				'delete'
			];
		} else {
			$actions = ['edit'];
		}
		$links = [
			[
				'add',
				['controller' => 'podcast_episodes', 'action' => 'add', $podcast['Podcast']['id']]
			]
		];
		echo $this->element('editor_panel', compact('actions', 'prefix', 'links') + ['id' => $podcast['Podcast']['id']]);
	endif; ?>

	<section>
		<div class="panel panel-default">
			<div class="panel-body">
				<h2 class="page-title">Episodes</h2>
				<?php echo $this->element('podcast_episodes/list'); ?>
			</div>
		</div>
	</section>
</div>


