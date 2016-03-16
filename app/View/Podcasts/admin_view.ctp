<?php 
/*
echo $this->Layout->infoTable([
	'Title' => $podcast['Podcast']['title'],
	//'Downloads' => number_format($podcast['Podcast']['episode_downloads']),
]);
*/
echo $this->Html->link(
	'Public View', 
	['action' => 'view', $podcast['Podcast']['id'], 'admin' => false],
	['class' => 'btn btn-default btn-lg']
);

echo $this->FieldUploadImage->image($podcast['Podcast'], 'thumbnail', 'banner-share', ['modified' => true]);
echo $this->Html->link(
	'Resize',
	[
		'controller' => 'field_upload', 
		'action' => 'edit', 
		'Podcast', 
		$podcast['Podcast']['id'], 
		'thumbnail', 
		'banner-share',
		'plugin' => 'uploadable',
		'admin' => false,
	],
	['class' => 'btn btn-primary']
);