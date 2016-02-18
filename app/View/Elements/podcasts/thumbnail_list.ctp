<div class="thumbnail-list podcast-thumbnail-list">
	<?php foreach ($podcasts as $podcast): 
		if (!empty($podcast['Podcast'])) {
			$podcast = $podcast['Podcast'];
		}
		$url = Router::url(['controller' => 'podcasts', 'action' => 'view', $podcast['id']]);
		?>
		<a href="<?php echo $url; ?>" class="thumbnail-list-item">
			<?php echo $this->FieldUploadImage->image($podcast, 'thumbnail', 'thumbnail-lg', ['class' => 'thumbnail-list-item-img']); ?>
			<div class="thumbnail-list-item-caption">
				<h2 class="thumbnail-list-item-title"><?php echo $podcast['title']; ?></h2>
				<?php echo $podcast['description']; ?>
			</div>
		</a>
	<?php endforeach; ?>
</div>