<?php
$this->layout = 'default_slim';
?>
<div class="user-view">
	<div class="user-view-heading">
		<div class="media">
			<?php echo $this->FieldUploadImage->image($user['User'], 'thumbnail', 'thumbnail-lg', ['class' => 'pull-left media-object']); ?>
			<div class="media-body">
				<h2 class="media-title"><?php echo $user['User']['name']; ?></h2>
			</div>
		</div>
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
endif;
?>