<?php 
/*
echo $this->Layout->infoTable([
	'Title' => $podcast['Podcast']['title'],
	//'Downloads' => number_format($podcast['Podcast']['episode_downloads']),
]);
*/
echo $this->Html->link(
	'Public View', 
	['action' => 'view', 'slug' => $podcast['Podcast']['slug'], 'admin' => false],
	['class' => 'btn btn-default btn-lg']
);

$resize = [
	'banner' => 'Banner (Website)',
	'banner-share' => 'Banner (Facebook)',
];

foreach ($resize as $field => $title): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title"><?php echo $title; ?></div>
		</div>
		<div class="panel-body">
			<?php echo $this->FieldUploadImage->image($podcast['Podcast'], 'banner', $field, [
				'style' => 'max-width:600px', 
				'modified' => true
			]); ?>
		</div>
		<div class="panel-footer">
			<?php 
			echo $this->Html->link(
				'Resize',
				[
					'controller' => 'field_upload', 
					'action' => 'edit', 
					'Podcast', 
					$podcast['Podcast']['id'], 
					'banner', 
					$field,
					'plugin' => 'uploadable',
					'admin' => false,
				],
				['class' => 'btn btn-primary']
			);
			?>
		</div>
	</div>
<?php endforeach;