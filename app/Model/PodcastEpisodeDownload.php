<?php 
class PodcastEpisodeDownload extends AppModel {
	public $belongsTo = [
		'PodcastEpisode' => [
			'counterCache' => 'download_count',
		]
	];	
}