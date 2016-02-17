<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'pageTitle' => 'Sign In',
	'submitText' => 'Sign In',
]);
echo $this->Form->create();
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->input('remember_me', [
	'default' => 1,
	'type' => 'checkbox',
	'class' => 'checkbox',
]);
