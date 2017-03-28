<?php
$this->Html->css('elements/articles_small_list', null, ['inline' => false]);
?>
<ul class="articles-small-list">
	<?php foreach ($articles as $article): 
		$article = $article['Article'];
		$url = [
			'controller' => 'articles',
			'action' => 'view',
			'id' => $article['id'],
			'slug' => $article['slug'],
		];
		?>
		<li>
			<a href="<?php echo Router::url($url, true); ?>">
				<?php echo $article['title']; ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>