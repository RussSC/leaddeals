<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'formOptions' => ['type' => 'file'],
	'pageTitle' => 'Update Podcast',
]);
echo $this->Form->create();

echo $this->Form->hidden('id');
echo $this->Form->hidden('podcast_id');
echo $this->Form->input('episode_number');
echo $this->Form->input('title');
echo $this->Form->input('description');


$inputDefaults = $this->Form->inputDefaults();
$this->Form->inputDefaults([
	'div' => 'form-group col col-xs-4',
	'default' => '00',
	'type' => 'text',
	'placeholder' => '00',
], true);
?>
<div class="row">
	<?php 
	echo $this->Form->input('duration_hh', ['label' => 'HH']);
	echo $this->Form->input('duration_mm', ['label' => 'MM']);
	echo $this->Form->input('duration_ss', ['label' => 'SS']);
	?>
</div>
<?php
$this->Form->inputDefaults($inputDefaults);

echo $this->Form->input('posted');

// Add these fields
echo $this->Form->input('download_url');

echo $this->Form->input('active', [
	'class' => 'checkbox',
	'afterInput' => '<span class="help-block">Is this ready to be published?</span>'
]);

echo $this->Form->input('explicit', [
	'label' => 'Explicit Content',
	'class' => 'checkbox',
	'afterInput' => '<span class="help-block">Does this podcast have explicit content? (Needed for iTunes)</span>',
]);
