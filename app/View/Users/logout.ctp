<?php
$this->layout = 'CenteredContent.form';
$this->set('centeredContent', [
	'pageTitle' => 'Logged Out',
	'submitText' => 'Log Back In',
	'submitUrl' => ['controller' => 'users', 'action' => 'login', 'redirect' => 0],
	'backText' => 'Home',
	'backUrl' => '/',
]);
?>
You have been successfully logged out
