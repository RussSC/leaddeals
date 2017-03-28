<?php
$body = $article['Article']['body'];
$body = $this->EmbeddedImage->replace($body, $article);
$body = $this->DisplayText->text($body);
?>

<?php echo $this->Html->css('views/article-view', null, ['inline' => false]); ?>
<div class="article-view">
	<?php if ($isAdmin || $canEdit || $canDelete): 
		if ($isAdmin) {
			//$prefix = 'admin';
			$actions = ['edit', 'delete'];
		} else {
			$prefix = false;
			$actions = [];
			if ($canEdit) {
				$actions[] = 'edit';
			}
			if ($canDelete) {
				$actions[] = 'delete';
			}
		}
		$links = [];
		echo $this->element('editor_panel', compact('actions', 'prefix', 'links') + ['id' => $article['Article']['id']]);
	endif; ?>

	<header class="article-view-header">
		<?php if ($banner = $this->FieldUploadImage->image($article['Article'], 'banner', 'banner', [
				'class' => 'article-view-header-banner',
				'modified' => true
			])):
				echo $banner;
		endif; ?>
		<h1 class="article-view-header-title"><?php echo $article['Article']['title']; ?></h1>
		<div class="article-view-header-byline">
			Posted on <?php echo date('n/j/y', strtotime($article['Article']['created'])); ?>
			<?php if (!empty($article['User'])): ?>
				by <?php echo $this->Html->link($article['User']['name'], [
					'controller' => 'users',
					'action' => 'view',
					$article['User']['id']
				]); ?>
			<?php endif; ?>
		</div>
	</header>
	<main class="article-view-body">
		<?php echo $body; ?>
		<?php echo $this->ShareLink->share([
			'controller' => 'articles',
			'action' => 'view',
			'id' => $article['Article']['id'],
			'slug' => $article['Article']['slug']
		], $article['Article']['title']); ?>
	</main>
	<footer class="article-view-footer">
		<?php if (!empty($article['User'])): 
			$url = ['controller' => 'users', 'action' => 'view', $article['User']['id']];
			?>
			<section>
				<div class="article-view-footer-credit">
					<?php echo $this->FieldUploadImage->image($article['User'], 'thumbnail', 'thumbnail-lg', [
						'class' => 'article-view-footer-credit-thumb',
						'url' => $url,
					]); ?>
					<main>
						<h6 class="article-view-footer-credit-title">Written by <?php echo $this->Html->link($article['User']['name'], $url); ?></h6>
						<?php if (!empty($article['User']['UserLink'])): ?>
							<?php echo $this->element('user_links/list', ['userLinks' => $article['User']['UserLink']]); ?>
						<?php endif; ?>
					</main>
				</div>
			</section>
		<?php endif; ?>

		<?php echo $this->element('articles/view/related'); ?>

	</footer>
</div>

