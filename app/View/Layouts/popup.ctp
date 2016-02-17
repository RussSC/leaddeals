<?php 
$this->extend('default');
$this->Html->css('views/popup', null, ['inline' => false]);
echo $this->fetch('content');