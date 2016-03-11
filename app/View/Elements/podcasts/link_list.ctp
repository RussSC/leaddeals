<div class="media-list">
<?php foreach ($podcasts as $podcast): 
	$url = Router::url(['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcast['Podcast']['slug']], true);
	?>
	<a href="<?php echo $url; ?>" class="media">
		<?php echo $this->FieldUploadImage->image(
			$podcast['Podcast'], 'thumbnail', 'thumbnail',
			['class' => 'pull-left media-object']
		); ?>
		<div class="media-body">
			<h4 class="media-title"><?php echo $podcast['Podcast']['title']; ?></h4>
			<?php echo $podcast['Podcast']['description']; ?>
		</div>
	</a>
<?php endforeach; ?>
</div>