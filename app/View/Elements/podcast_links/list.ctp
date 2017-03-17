<div class="share-link-list">
<?php foreach ($podcastLinks as $podcastLink):
	echo $this->ShareLink->link($podcastLink['url'], $podcastLink['type'], [
		'class' => 'btn btn-default',
	]) . ' ';
endforeach; ?>
</div>