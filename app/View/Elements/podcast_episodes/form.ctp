<?php

if (!$this->Form->value('PodcastEpisode.id')) {
	$status = 'adding';
} else if (!$this->Form->value('PodcastEpisode.download_url')) {
	$status = 'uploading';
} else {
	$status = 'edit';
}

$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'formOptions' => ['type' => 'file'],
	'pageTitle' => 'Update Podcast',
]);
echo $this->Form->create();

echo $this->Form->hidden('id');
echo $this->Form->hidden('podcast_id');
echo $this->Form->hidden('filesize');

if ($status == 'uploading'): ?>
	<div class="alert alert-info">
		<h3 class="alert-title">Add File Information</h3>
		<div class="lead">
			<p>Now you need to go upload your podcast file to your host. Be sure to copy over the title and description from below.</p>
		</div>
		<?= $this->Form->inputCopy($this->Form->value('PodcastEpisode.title'), ['label' => 'Copy the Title']); ?>
		<?= $this->Form->inputCopy($this->Form->value('PodcastEpisode.description'), ['label' => 'Copy the Description']); ?>
		<?= $this->Form->inputCopy(Router::url([
			'action' => 'view', 
			'id' => $this->Form->value('PodcastEpisode.id'), 
			'slug' => Inflector::slug($this->Form->value('PodcastEpisode.title'))
		], true), ['label' => 'Copy the Podcast URL']); ?>

		<div class="lead">
			<p>Once it's uploaded, copy some information from the Libsyn uploading page and paste it here:</p>
		</div>

		<?= $this->element('podcast_episodes/form/file_input') ?>
		<?= $this->element('podcast_episodes/form/itunes_input'); ?>
		<?= $this->element('podcast_episodes/form/active_input'); ?>
		<?= $this->Form->button('Update', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']); ?>
	</div>
	<hr />
<?php endif;

echo $this->FieldUploadImage->input('banner', [
	'label' => 'Banner Image',
	'size' => 'banner', 
	'fromUrl' => true
]);
echo $this->FieldUploadImage->input('thumbnail', [
	'label' => 'Thumbnail (Square Image)',
	'size' => 'thumbnail', 
	'fromUrl' => true
]);

echo $this->Form->input('episode_number');
echo $this->Form->input('title');
echo $this->Form->input('description', [
	'help' => 'NOTE: Avoid swearing or referencing bad stuff in the description. It makes iTunes sad',
]);

if ($status == 'adding'):
	echo $this->Form->hidden('active', ['value' => 0]);
	echo $this->Form->hidden('explicit');
	echo $this->Form->hidden('keywords');
	
	echo $this->element('podcast_episodes/form/published_input');
	?>
	<div class="alert alert-info">
		Before going any farther, we need to hit submit to create the podcast page.
	</div>
<?php else:
	if ($status == 'edit') {
		echo $this->element('podcast_episodes/form/file_input');
		echo $this->element('podcast_episodes/form/itunes_input');
		echo $this->element('podcast_episodes/form/published_input');
		echo $this->element('podcast_episodes/form/active_input');
	} else {
		echo $this->element('podcast_episodes/form/published_input');
	}
endif;