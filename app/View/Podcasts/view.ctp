<?php
$mainButtons = [
	[
		'title' => 'Feed',
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
?>

<?php $this->Html->css('views/podcast-view', null, ['inline' => false]); ?>

<?php if (empty($podcast['Podcast']['active'])): ?>
	<div class="alert alert-warning">
		<h2 class="alert-title">Podcast is Inactive</h2>
		This podcast is listed as inactive, and can only be viewed by admins until it's activated.
	</div>
<?php endif; ?>

<div class="podcast-view-bg">
	<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'banner', 'banner'); ?>
</div>


<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="podcast-view">

			<div class="text-center">
				<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'thumbnail-md'); ?>
				<h1 class="podcast-view-title">
					<?php echo $podcast['Podcast']['title']; ?>
				</h1>
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<div class="podcast-view-heading">
						<div class="podcast-view-heading-banner">
						</div>
						<div class="podcast-view-heading-body">
							<div class="text-center">
								<?php foreach ($mainButtons as $config):
									echo $this->Html->link(
										$config['icon'] . ' ' . $config['title'],
										$config['url'],	[
											'escape' => false, 
											'class' => 'btn btn-primary btn-lg', 
											'title' => $config['urlTitle'], 
											'target' => '_blank'
										]
									); 
									echo ' ';
								endforeach; ?>
							</div>
							<div>
								<?php if (!empty($podcast['Podcast']['subtitle'])): ?>
									<h3 class="podcast-view-subtitle">
										<?php echo $podcast['Podcast']['subtitle']; ?>
									</h3>
								<?php endif; ?>
								<?php if (!empty($podcast['Podcast']['description'])): ?>
									<div class="podcast-view-description">
										<?php echo $this->DisplayText->text($podcast['Podcast']['description']); ?>
									</div>
								<?php endif; ?>

								<?php if (!empty($podcast['User'])): ?>
									<p class="text-center">
										<strong>Authors:</strong>
										<?php
										$users = [];
										foreach ($podcast['User'] as $user):
											$users[] = $this->Html->link(
												$user['name'], 
												['controller' => 'users', 'action' => 'view', $user['id']]											
											);
										endforeach; 
										echo implode(', ', $users);
										?>
									</p>
								<?php endif; ?>
							</div>
						</div>

						<?php if (!empty($podcast['PodcastLink'])): ?>
							<div class="share-link-list">
							<?php foreach ($podcast['PodcastLink'] as $podcastLink):
								echo $this->ShareLink->link($podcastLink['url'], $podcastLink['type'], [
									'class' => 'btn btn-default',
								]) . ' ';
							endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="podcast-view-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Episodes</div>
					</div>
					<?php echo $this->element('podcast_episodes/list'); ?>
				</div>
			</div>

			<?php if (!empty($isEditor)): 
				if ($this->Session->read('Auth.User.is_admin')) {
					$prefix = 'admin';
					$actions = ['view', 'edit', 'delete'];
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
		</div>
	</div>
</div>

<?php
/* Removing the right side to better improve the episodes below
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

*/
?>

