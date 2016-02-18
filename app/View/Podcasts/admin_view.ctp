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