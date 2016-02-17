<?php $this->Html->css('views/podcast-view', null, ['inline' => false]); ?>
<div class="row">
	<div class="col-sm-8">

		<div class="podcast-view">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="podcast-view-heading">
						<div class="podcast-view-heading-banner">
							<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'banner', 'banner'); ?>
						</div>
						<div class="podcast-view-heading-body">
							<h2 class="podcast-view-title">
								<div class="pull-right">
									<?php echo $this->Html->link(
										'<i class="fa fa-rss"></i> FEED',
										['controller' => 'podcasts', 'action' => 'feed', $podcast['Podcast']['id']],
										['escape' => false, 'class' => 'btn btn-default', 'title' => 'RSS Feed']
									); ?>

								</div>
								<?php echo $podcast['Podcast']['title']; ?>
							</h2>
							<?php if (!empty($podcast['User'])): ?>
								<h3 class="podcast-view-subtitle">
									<strong>Authors:</strong>
									<?php foreach ($podcast['User'] as $user):
										echo $this->Html->link(
											$user['name'], 
											['controller' => 'users', 'action' => 'view', $user['id']]											
										);
									endforeach; ?>
								</h3>
							<?php endif; ?>

						
							<div class="media">
								<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'thumbnail-md', ['class' => 'pull-left media-object']); ?>
								<div class="media-body">
									<?php echo nl2br($podcast['Podcast']['description']); ?>
								</div>
							</div>
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
				echo $this->element('editor_panel', compact('actions', 'prefix', 'links'));
			endif; ?>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Recent Episodes</div>
			</div>
			<?php echo $this->element('podcast_episodes/thumbnail_link_list', [
				'result' => $recentEpisodes,
			]); ?>
		</div>
	</div>
</div>