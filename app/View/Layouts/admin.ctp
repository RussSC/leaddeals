<?php
$this->extend('default');
$this->start('header'); ?>
	<h1 class="layout-header-heading">Admin Section</h1>
<?php $this->end();

echo $this->fetch('content');