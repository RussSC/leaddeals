<div class="media-list">
<?php foreach ($podcasts as $podcast): 
	if (!empty($podcast['Podcast'])) {
		$podcast = $podcast['Podcast'];
	}
	$url = Router::url(['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcast['slug']], true);
	?>
	<a href="<?php echo $url; ?>" class="media">
		<?php echo $this->FieldUploadImage->image(
			$podcast, 'thumbnail', 'thumbnail',
			['class' => 'pull-left media-object']
		); ?>
		<div class="media-body">
			<h4 class="media-title"><?php echo $podcast['title']; ?></h4>
			<?php echo $podcast['description']; ?>
		</div>
	</a>
<?php endforeach; ?>
</div>