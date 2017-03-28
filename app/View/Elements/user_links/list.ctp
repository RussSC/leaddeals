<div class="share-link-list">
<?php foreach ($userLinks as $userLinks):
	echo $this->ShareLink->link($userLinks['url'], $userLinks['type'], [
		'class' => 'btn btn-default',
	]) . ' ';
endforeach; ?>
</div>