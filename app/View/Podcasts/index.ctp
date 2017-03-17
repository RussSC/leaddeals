<h1 class="page-title">Podcasts</h1>

<section>
	<?php echo $this->element('podcasts/thumbnail_list', ['class' => 'thumbnail-list thumbnail-list-center']); ?>
</section>

<hr/>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Recent Episodes</div>
	</div>
	<div class="panel-body">
		<?php echo $this->element('podcast_episodes/thumbnail_list', [
			'podcastEpisodes' => $recentEpisodes,
		]); ?>
	</div>
</div>

<?php if ($this->Session->check('Auth.User.is_admin')): 
	echo $this->element('editor_panel', [
		'actions' => ['index', 'add'],
		'prefix' => 'admin',
	]); 
endif; ?>