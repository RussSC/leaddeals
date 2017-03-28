<?php
$View = $this;

$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'formOptions' => ['type' => 'file'],
]);
echo $this->Form->create('User', ['type' => 'file']);
echo $this->Form->hidden('id');
echo $this->Form->hidden('is_admin');
?>
<fieldset>
	<legend>Login</legend>
	<?php 
	echo $this->Form->input('email', ['type' => 'email']);
	echo $this->Form->input('new_password', ['type' => 'password']);
	?>
</fieldset>

<fieldset>
	<legend>About</legend>
	<?php
	echo $this->Form->input('name', [
		'label' => 'Your Name',
	]);
	echo $this->Form->input('about', [
		'help' => 'Write a little about yourself',
	]);
	echo $this->FieldUploadImage->input('thumbnail', [
		'size' => 'thumbnail-lg',
		'fromUrl' => true,
	]);
	?>
</fieldset>

<fieldset>
	<legend>Social Media Links</legend>
	<span class="help-block">Please list any forms of social media you're comfortable listing on the site for people to reach you</span>

	<?php echo $this->element('Layout.form/element_input_list', [
		'model' => 'UserLink',
		'count' => 0,
		'function' => function($count) use ($View) {
			$prefix = "UserLink.$count";
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
