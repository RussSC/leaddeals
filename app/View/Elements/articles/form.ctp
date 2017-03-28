<?php
$View = $this;
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'contentTitle' => 'Article',
	'formOptions' => [
		'type' => 'file',
	]
]);
$this->Form->create();

echo $this->Form->hidden('id');
echo $this->Form->hidden('user_id', ['default' => CakeSession::read('Auth.User.id')]);

echo $this->Form->input('active', [
	'label' => 'Active',
	'help' => 'Is this ready to be published?',
]);

echo $this->Form->input('published', [
	'label' => 'Publish',
	'div' => 'form-group form-group-datetime',
	'help' => 'When should this article be published',
]);

echo $this->FieldUploadImage->input('banner', [
	'fromUrl' => true,
	'size' => 'banner',
]);
echo $this->FieldUploadImage->input('thumbnail', [
	'size' => 'thumbnail',
	'fromUrl' => true,
]);
echo $this->Form->input('title');
echo $this->Form->input('body', [
	'rows' => 40,
]);

echo $this->EmbeddedImage->input();
?>

<?php if (!empty($podcastEpisodes)): ?>
	<fieldset>
		<legend>Podcast Episode</legend>
		<span class="help-block">Is this article about a specific podcast episode?</span>
		<?php 
		/*
		echo $this->element('Layout.form/element_input_list', [
			'model' => 'ArticlesPodcastEpisode',
			'count' => 1,
			'function' => function($count) use ($View, $podcastEpisodes) {
				$prefix = "ArticlesPodcastEpisode.$count";
				$out =  $View->Form->hidden("$prefix.id", ['class' => 'element-input-list-key']);
				$out .= $View->Form->input("$prefix.podcast_episode_id", [
					'options' => ['' => ' -- Select an Episode -- '] + $podcastEpisodes,
					'label' => false,
				]);
				return $out;
			}
		]);
		*/
		echo $this->element('Layout.form/element_input_list', [
			'model' => 'PodcastEpisode.PodcastEpisode',
			'function' => function($count) use ($View, $podcastEpisodes) {
				$out = $View->Form->input("PodcastEpisode.PodcastEpisode.$count", [
					'options' => ['' => ' -- Select an Episode -- '] + $podcastEpisodes,
					'class' => 'element-input-list-key form-control',
					'label' => false,
					'required' => false,
				]);
				return $out;
			}
		]);
		?>
	</fieldset>
<?php endif; ?>

<?php if (!empty($podcasts)): ?>
	<fieldset>
		<legend>Podcasts</legend>
		<span class="help-block">Is this article about a podcast? (You don't have to include this if you added episodes above)</span>
		<?php 

		echo $this->element('Layout.form/element_input_list', [
			'model' => 'Podcast.Podcast',
			'function' => function($count) use ($View, $podcasts) {
				$out = $View->Form->input("Podcast.Podcast.$count", [
					'options' => ['' => ' -- Select a Podcast -- '] + $podcasts,
					'class' => 'form-control element-input-list-key',
					'label' => false,
					'required' => false,
				]);
				return $out;
			}
		]);
		/*
		echo $this->element('Layout.form/element_input_list', [
			'model' => 'ArticlesPodcast',
			'count' => 1,
			'function' => function($count) use ($View, $podcasts) {
				$prefix = "ArticlesPodcast.$count";
				$out =  $View->Form->hidden("$prefix.id", ['class' => 'element-input-list-key']);
				$out .= $View->Form->input("$prefix.podcast_id", [
					'options' => ['' => ' -- Select a Podcast -- '] + $podcasts,
					'label' => false,
				]);
				return $out;
			}
		]);
		*/
		?>
	</fieldset>
<?php endif; ?>
