<?php
$this->layout = 'default_slim';
$this->Html->css('views/user-view', null, ['inline' => false]);

?>
<div class="user-view">
	<div class="user-view-heading">
		<?php echo $this->FieldUploadImage->image($user['User'], 'thumbnail', 'banner', ['class' => 'user-view-heading-banner']); ?>
		<h2 class="user-view-heading-title"><?php echo $user['User']['name']; ?></h2>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Podcasts</div>
		</div>
		<div class="panel-body">
			<?php echo $this->element('podcasts/thumbnail_list', ['podcasts' => $user['Podcast']]); ?>
		</div>
	</div>
	
</div>
<?php if ($isEditor):
	echo $this->element('editor_panel', ['actions' => ['edit']]);
	?>
	<div class="panel panel-admin">
		<div class="panel-heading">
			<div class="panel-title">Resize Images</div>
		</div>
		<div class="panel-body">
			<?php
			$sizes = ['thumbnail', 'banner'];
			echo $this->element('editor/image_resize', [
				'model' => 'User',
				'field' => 'thumbnail',
				'result' => $user['User'],
				'sizes' => $sizes,
				'id' => $user['User']['id'],
			]); 
			?>
		</div>
	</div>
<?php endif;