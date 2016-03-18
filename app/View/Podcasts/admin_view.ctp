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

?>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
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
	</div>
</div>