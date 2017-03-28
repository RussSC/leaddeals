<?php 
/*
echo $this->Layout->infoTable([
	'Title' => $podcast['Podcast']['title'],
	//'Downloads' => number_format($podcast['Podcast']['episode_downloads']),
]);
*/
$this->layout = 'CenteredContent.default';
$this->set('centeredContent', [
	'contentTitle' => 'Podcast Admin',
]);
$this->start('beforeContent');
echo $this->element('layouts/admin/header');
$this->end();


echo $this->Html->link(
	'Public View', 
	['action' => 'view', 'slug' => $podcast['Podcast']['slug'], 'admin' => false],
	['class' => 'btn btn-primary btn-lg']
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Image Resizing</div>
	</div>
	<div class="panel-body">
		<?php 
		echo $this->element('editor/image_resize', [
			'model' => 'Podcast',
			'field' => 'banner',
			'sizes' => [
				[
					'size' => 'banner',
					'title' => 'Banner (Website)',
				], [
					'size' => 'banner-share',
					'title' => 'Banner (Facebook)',
				],
			],
			'result' => $podcast['Podcast'],
			'id' => $podcast['Podcast']['id'],
		]);
		?>
	</div>
</div>
