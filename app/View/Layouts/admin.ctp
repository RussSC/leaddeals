<?php
$this->extend('html');
$this->start('header'); ?>
<h1>Admin Section</h1>
<?php $this->end();

echo $this->fetch('content');