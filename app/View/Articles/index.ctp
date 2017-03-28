<h1 class="page-title">News</h1>
<?php echo $this->element('ResultFilter.heading'); ?>
<!-- Article List -->
<?php echo $this->Html->css('views/article-list', null, ['inline' => false]); ?>
<?php echo $this->element('paginate'); ?>
<ul class="article-list">
	<?php foreach ($articles as $article): 
		$url = Router::url(['controller' => 'articles', 'action' => 'view', $article['Article']['id']], true);
		$first = explode("\n", $article['Article']['body']);
		$first = array_shift($first);
		$first = $this->Text->truncate($first, 400);

		?>
		<li class="article-list-item">
			<a href="<?php echo $url; ?>">
				<?php echo $this->FieldUploadImage->image($article['Article'], 'thumbnail', 'thumbnail-lg', ['class' => 'article-list-item-thumb']); ?>
				<main>
					<h2 class="article-list-item-title"><?php echo $article['Article']['title']; ?></h2>
					<h3 class="article-list-item-byline">Posted on 
						<?php echo date('n/j/y', strtotime($article['Article']['published'])); ?> by 
						<strong><?php echo $article['User']['name']; ?></strong>
					</h3>
					<p><?php echo $first; ?></p>
				</main>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
<?php echo $this->element('paginate'); ?>
<!-- Article List End -->

<?php if ($isAdmin): 
	$actions = ['add'];
	$links = [];
	echo $this->element('editor_panel', compact('actions', 'prefix', 'links'));
endif; ?>
