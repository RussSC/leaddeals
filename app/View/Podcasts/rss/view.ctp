<?php 
$this->set('channelData', [
	'title' => __($podcast['Podcast']['title']),
	'link' => Router::url(['controller' => 'podcasts', 'action' => 'view', $podcast['Podcast']['id']], true),
	'description' => $podcast['Podcast']['title'],
	'language' => 'en-us',
]);
foreach ($podcast['PodcastEpisode'] as $episode):
	$url = [
		'controller' => 'podcast_episodes',
		'action' => 'view',
		$episode['id'],
	];

	$body = h(strip_tags($episode['description']));
	$body = $this->Text->truncate($body, 400, [
		'ending' => '...',
		'exact' => true,
		'html' => true,
	]);

	echo $this->Rss->item([], [
		'title' => $episode['title'],
		'link' => Router::url($url, true),
		'guid' => ['url' => $url, 'isPermalink' => 'true'],
		'description' => $body,
		'pubDate' => $episode['posted']
	]);

endforeach;