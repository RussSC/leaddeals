<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'pageTitle' => 'Sign In',
	'submitText' => 'Sign In',
]);
$this->Form->create();

echo $this->Form->hidden('redirect');
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->input('remember_me', [
	'default' => 1,
	'type' => 'checkbox',
	'class' => 'checkbox',
]);
