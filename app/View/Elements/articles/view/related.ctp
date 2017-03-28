<?php
ob_start();
if (!empty($article['Podcast'])): ?>
	<section>
		<h4 class="section-title">Podcasts</h4>
		<?php foreach ($article['Podcast'] as $podcast): 
			$url = Router::url(['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcast['slug']], true);
			?>
			<a href="<?php echo $url; ?>" class="article-view-footer-credit">
				<?php echo $this->FieldUploadImage->image($podcast, 'thumbnail', 'thumbnail-lg', [
					'class' => 'article-view-footer-credit-thumb'
				]); ?>
				<main>
					<h6 class="article-view-footer-credit-title"><?php echo $podcast['title']; ?></h6>
				</main>
			</a>
		<?php endforeach; ?>
	</section>
<?php endif; 

if (!empty($article['PodcastEpisode'])): ?>
	<section>
		<h4 class="section-title">Podcast Episodes</h4>
		<?php foreach ($article['PodcastEpisode'] as $podcastEpisode): 
			$url = Router::url([
				'controller' => 'podcast_episodes', 
				'action' => 'view', 
				'id' => $podcastEpisode['id'], 
				'slug' => $podcastEpisode['slug']
			], true);
			?>
			<a href="<?php echo $url; ?>" class="article-view-footer-credit">
				<?php echo $this->FieldUploadImage->image($podcastEpisode, 'thumbnail', 'thumbnail-lg', [
					'class' => 'article-view-footer-credit-thumb'
				]); ?>
				<main>
					<h6 class="article-view-footer-credit-title"><?php echo $podcastEpisode['full_title']; ?></h6>
				</main>
			</a>
		<?php endforeach; ?>
	</section>
<?php endif;

if ($out = ob_get_clean()): ?>
	<div class="article-view-related">
		<h3>Related</h3>
		<?php echo $out; ?>
	</div>
<?php endif;
