<?php 
$View = $this;

$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'formOptions' => ['type' => 'file'],
	'pageTitle' => 'Update Podcast',
]);
echo $this->Form->create();

echo $this->Form->hidden('id');
echo $this->Form->input('title');

echo $this->Form->input('slug');
echo $this->Form->input('auto_slug');

echo $this->Form->input('description',[
	'help' => 'NOTE: Avoid swearing or referencing bad stuff in the description. It makes iTunes sad',
]);

echo $this->Form->input('keywords', [
	'help' => 'To assist with finding the podcast',
]);
echo $this->Form->input('active');
echo $this->Form->input('explicit', [
	'class' => 'checkbox',
	'help' => 'Does this podcast contain explicit content?',
]);
echo $this->Form->input('itunes_url', [
	'label' => 'iTunes URL',
	'help' => 'The URL to link back to the iTunes profile',
]);
echo $this->Form->input('category', [
	'help' => 'A category is needed to add it to iTunes',
	'default' => 'Comedy',
]);
?>
<fieldset>
	<legend>Links</legend>
	<?php echo $this->element('Layout.form/element_input_list', [
		'model' => 'PodcastLink',
		'count' => 0,
		'function' => function($count) use ($View) {
			$prefix = "PodcastLink.$count";
			$out = $View->Form->hidden("$prefix.id");
			$out .= $View->Form->hidden("$prefix.podcast_id");
			$out .= '<div class="row">';
			$out .= '<div class="col-sm-4">' . $View->ShareLink->inputTypeSelect("$prefix.type") . '</div>';
			$out .= '<div class="col-sm-8">' . $View->Form->input("$prefix.url") . '</div>';
			$out .= '</div>';
			return $out;
		}
	]); ?>
</fieldset>
<fieldset>
	<legend>Authors</legend>
	<?php 
	echo $this->element('Layout.form/element_input_list', [
		'model' => 'PodcastsUser',
		'count' => 0,
		'function' => function($count) use ($View, $users) {
			$out =  $View->Form->hidden('PodcastsUser.' . $count . '.id', ['class' => 'element-input-list-key']);
			$out .= $View->Form->input('PodcastsUser.' . $count . '.user_id', [
				'options' => ['' => ' -- Select a User -- '] + $users,
				'label' => false,
			]);
			return $out;
		}
	]);
	?>
</fieldset>

<fieldset>
	<legend>Images</legend>
	<?php
	echo $this->FieldUploadImage->input('thumbnail', ['size' => 'thumbnail-sm']);
	echo $this->FieldUploadImage->input('banner', ['size' => 'banner']);
	?>
</fieldset>
