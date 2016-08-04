<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'formOptions' => ['type' => 'file'],
	'pageTitle' => 'Update Podcast',
]);
echo $this->Form->create();

echo $this->Form->hidden('id');
echo $this->Form->hidden('podcast_id');

echo $this->FieldUploadImage->input('banner', ['size' => 'banner']);
echo $this->Form->input('episode_number');
echo $this->Form->input('title');
echo $this->Form->input('description', [
	'afterInput' => '<span class="help-block">NOTE: Avoid swearing or referencing bad stuff in the description. It makes iTunes sad</span>',
]);

echo $this->Form->input('posted', [
	'div' => 'form-group input-date',
	'label' => 'Date Posted',
	'afterInput' => '<span class="help-block">Post in the future to delay posting</span>',
]);

echo $this->Form->input('active', [
	'class' => 'checkbox',
	'afterInput' => '<span class="help-block">Is this ready to be published?</span>'
]);

?>
<fieldset>
	<legend>File Information</legend>
	<?php
	// Add these fields
	echo $this->Form->input('download_url', [
		'label' => 'Download URL',
		'afterInput' => '<span class="help-block">Paste the URL where the mp3 is stored</span>',
	]);

	echo $this->Form->input('libsyn_id', [
		'label' => 'Libsyn ID',
		'help' => 'If you want to use the Libsyn player, add the Libsyn episode ID',
		'type' => 'number',
	]);

	$inputDefaults = $this->Form->inputDefaults();
	$this->Form->inputDefaults([
		'div' => 'form-group col col-xs-4',
		'default' => '00',
		'type' => 'text',
		'placeholder' => '00',
	], true);
	?>
	<label>File length</label>
	<div class="row">
		<?php 
		echo $this->Form->input('duration_hh', ['label' => 'HH']);
		echo $this->Form->input('duration_mm', ['label' => 'MM']);
		echo $this->Form->input('duration_ss', ['label' => 'SS']);
		?>
	</div>
	<?php
	$this->Form->inputDefaults($inputDefaults);
	echo $this->Form->input('filesize', [
		'type' => 'number',
		'afterInput' => '<span class="help-block">How large the podcast is (in bytes)</span>',
	]);
	?>
</fieldset>
<fieldset>
	<legend>iTunes</legend>
	<?php
	echo $this->Form->input('explicit', [
		'label' => 'Explicit Content',
		'class' => 'checkbox',
		'afterInput' => '<span class="help-block">Does this podcast have explicit content? (Needed for iTunes)</span>',
	]);
	echo $this->Form->input('keywords', [
		'afterInput' => '<span class="help-block">To assist with finding the podcast</span>',
	]);
	?>
</fieldset>
