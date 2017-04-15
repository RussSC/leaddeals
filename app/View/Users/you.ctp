<?php $this->Html->css('views/user-view', null, ['inline' => false]); ?>
<div class="user-view">
	<h2>Your Profile</h2>
	<?php if (!empty($podcasts)): ?>
		<section>
			<header>
				<h3>Podcasts</h3>
			</header>
			<?php echo $this->element('podcasts/thumbnail_list', ['podcasts' => $podcasts]); ?>
		</section>
	<?php endif; ?>	

	<?php if (!empty($podcastEpisodes)): ?>
		<section>
			<header>
				<h3>Latest Podcast Episodes</h3>
			</header>
			<?php echo $this->element('podcast_episodes/thumbnail_list', ['podcastEpisodes' => $podcastEpisodes]); ?>
		</section>
	<?php endif; ?>	
</div>