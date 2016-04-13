<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">Image Resizing</div>
			</div>
			<div class="panel-body">
				<?php 
				echo $this->element('editor/image_resize', [
					'model' => 'PodcastEpisode',
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
					'result' => $podcastEpisode['PodcastEpisode'],
					'id' => $podcastEpisode['PodcastEpisode']['id'],
				]);
				?>
			</div>
		</div>
	</div>
</div>