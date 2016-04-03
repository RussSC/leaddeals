<?php
$mainButtons = [
	[
		'title' => 'RSS Feed',
		'icon' => '<i class="fa fa-rss"></i>',
		'url' => ['controller' => 'podcasts', 'action' => 'feed', 'slug' => $podcast['Podcast']['slug']],
	]
];
if (!empty($podcast['Podcast']['itunes_url'])):
	$mainButtons[] = [
		'title' => 'Subscribe via iTunes',
		'icon' => '<i class="fa fa-apple"></i>',
		'url' => str_replace('https://', 'itms://', $podcast['Podcast']['itunes_url']),
	];
endif;
?>

<?php $this->Html->css('views/podcast-view', null, ['inline' => false]); ?>

<?php if (empty($podcast['Podcast']['active'])): ?>
	<div class="alert alert-danger lead">
		<h2 class="alert-title">Podcast is Inactive</h2>
		This podcast is listed as inactive, and can only be viewed by admins until it's activated.
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-8">
		<div class="podcast-view">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="podcast-view-heading">
						<div class="podcast-view-heading-banner">
							<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'banner', 'banner', ['class' => 'podcast-view-heading-banner-img']); ?>
						</div>
						<div class="podcast-view-heading-body">
							<div class="text-center">
								<?php foreach ($mainButtons as $config):
									echo $this->Html->link(
										$config['icon'] . ' ' . $config['title'],
										$config['url'],	[
											'escape' => false, 
											'class' => 'btn btn-info btn-lg', 
											'title' => $config['title'], 
											'target' => '_blank'
										]
									); 
									echo ' ';
								endforeach; ?>
							</div>

							<div class="media">
								<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'thumbnail-md', ['class' => 'pull-right media-object']); ?>
								<div class="media-body">
									<h2 class="podcast-view-title">
										<?php echo $podcast['Podcast']['title']; ?>
									</h2>
									<?php if (!empty($podcast['Podcast']['subtitle'])): ?>
										<h3 class="podcast-view-subtitle">
											<?php echo $podcast['Podcast']['subtitle']; ?>
										</h3>
									<?php endif; ?>
									<?php if (!empty($podcast['Podcast']['description'])): ?>
										<div class="podcast-view-description">
											<?php echo nl2br($podcast['Podcast']['description']); ?>
										</div>
									<?php endif; ?>

									<?php if (!empty($podcast['User'])): ?>
										<p>
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
								<div class="share-link-list text-center">
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
			</div>

			<div class="podcast-view-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">Episodes</div>
					</div>
					<div class="panel-body">
						<?php echo $this->element('podcast_episodes/list'); ?>
					</div>
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