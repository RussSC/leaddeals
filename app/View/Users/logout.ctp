<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'pageTitle' => 'Logged Out',
	'submitText' => 'Log Back In',
	'submitUrl' => ['controllers' => 'users', 'action' => 'login'],
	'backText' => 'Home',
	'backUrl' => '/',
]);
?>
You have been successfully logged out
