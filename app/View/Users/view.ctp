<div class="user-view">
	<div class="user-view-heading">
		<div class="media">
			<?php echo $this->FieldUploadImage->image($user['User'], 'thumbnail', 'thumbnail-lg', ['class' => 'pull-left media-object']); ?>
			<div class="media-body">
				<h2 class="media-title"><?php echo $user['User']['name']; ?></h2>
			</div>
		</div>
	</div>
</div>