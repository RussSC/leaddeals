<div class="thumbnail-list podcast-thumbnail-list">
	<?php foreach ($podcasts as $podcast): 
		$url = Router::url(['controller' => 'podcasts', 'action' => 'view', $podcast['Podcast']['id']]);
		?>
		<a href="<?php echo $url; ?>" class="thumbnail-list-item">
			<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'thumbnail-lg', ['class' => 'thumbnail-list-item-img']); ?>
			<div class="thumbnail-list-item-caption">
				<h2 class="thumbnail-list-item-title"><?php echo $podcast['Podcast']['title']; ?></h2>
				<?php echo $podcast['Podcast']['description']; ?>
			</div>
		</a>
	<?php endforeach; ?>
</div>