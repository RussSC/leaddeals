<?php 
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

echo $this->Form->input('description');

echo $this->Form->input('active');
?>

<fieldset>
	<legend>Authors</legend>
	<?php 
	$View = $this;
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
