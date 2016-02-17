<h1>Podcasts</h1>
<div class="panel panel-default">
	<?php echo $this->element('podcasts/link_list'); ?>
</div>

<?php if ($this->Session->check('Auth.User.is_admin')): 
	echo $this->element('editor_panel', [
		'actions' => ['index', 'add'],
		'prefix' => 'admin',
	]); 
endif; ?>