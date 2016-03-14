<?php 
$this->Rss->registerNamespaces([
	'atom' => 'http://www.w3.org/2005/Atom',
	'cc' => 'http://web.resource.org/cc/',
	'itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
	'media' => 'http://search.yahoo.com/mrss/',
	'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
]);

$this->set('documentData', [
	'version' => '2.0',
]);

$ns = [
	'itunes' => [
		'prefix' => 'itunes',
		'url' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
	],
];

$viewUrl = Router::url(['controller' => 'podcasts', 'action' => 'view', 'slug' => $podcast['Podcast']['slug']], true);
$feedUrl = Router::url(['controller' => 'podcasts', 'action' => 'feed', 'slug' => $podcast['Podcast']['slug']], true);
$thumbnail = $this->FieldUploadImage->src($podcast['Podcast'], 'thumbnail', 'thumbnail-full');

$dateFormat = 'D, d Y H:i:s O';
$this->set('channelData', [
	'atom:link' => [
		'attrib' => [
			'rel' => 'self',
			'type' => 'application/rss+xml',
			'href' => $feedUrl,
		],
	],
	'title' => __($podcast['Podcast']['title']),
	'pubDate' => $this->Rss->time($podcast['Podcast']['created']),
	'lastBuildDate' => $this->Rss->time($podcast['Podcast']['modified']),
	'copyright' => $this->Rss->cdata('© Copyright ' . date('Y') . ' Lead Deals Productions'),
	'link' => $viewUrl,
	'language' => 'en-us',
	'managingEditor' => 'jamie@lead-deals.com (jamie@lead-deals.com)',
	'summary' => [
		'attrib' => ['namespace' => 'itunes'], 
		'cdata' => true, 
		'value' => $podcast['Podcast']['description']
	],
	'image' => [
		'url' => $thumbnail,
		'title' => $podcast['Podcast']['title'],
		'link' => [
			'attrib' => [],
			'cdata' => true,
			'value' => $viewUrl,
		]
	],
	'category' => [
		'attrib' => [
			'namespace' => 'itunes',
			'text' => $podcast['Podcast']['category'],
		],
	],
	'author' => [
		'attrib' => ['namespace' => 'itunes'],
		'value' => 'Lead Deals Productions',
	],
	
	'keywords' => [
		'attrib' => ['namespace' => 'itunes'],
		'value' => $podcast['Podcast']['keywords'],
	],
	'image' => [
		'attrib' => [
			'namespace' => 'itunes',
			'href' => $thumbnail,
		],
	],

	'explicit' => [
		'attrib' => ['namespace' => 'itunes'],
		'value' => !empty($podcast['Podcast']['explicit']) ? 'yes' : 'no',
	],
	'owner' => [
		'attrib' => ['namespace' => 'itunes'],
		'value' => /*
		[
			'name' => [
				'attrib' => ['namespace' => 'itunes'],
				'value' => 'Lead Deals Productions',
			],
			'email' => [
				'attrib' => ['namespace' => 'itunes'],
				'value' => 'podcasts@lead-deals.com',
			]
		]
		*/
		//	$this->Rss->elem('name', ['namespace' => 'itunes'], ['cdata' => true, 'value' => 'Lead Deals Productions']) .
		//	$this->Rss->elem('email', ['namespace' => 'itunes'], 'podcasts@lead-deals.com')
		'<itunes:name>Lead Deals Productions</itunes:name>' .
		'<itunes:email>' . 'podcasts@lead-deals.com' . '</itunes:email>'
		
	],
	'description' => [
		'attrib' => [],
		'cdata' => true,
		'value' => $podcast['Podcast']['description'],
	],
	'subtitle' => [
		'attrib' => ['namespace' => 'itunes'],
		'cdata' => true,
		'value' => $podcast['Podcast']['subtitle'],
	]

]);

foreach ($podcastEpisodes as $episode):
	$episode = $episode['PodcastEpisode'];
	$viewUrl = Router::url([
		'controller' => 'podcast_episodes',
		'action' => 'view',
		$episode['id'],
	], true);
	$downloadUrl = Router::url([
		'controller' => 'podcast_episodes',
		'action' => 'download',
		$episode['id'],
		'ext' => 'mp3',
	], true);

	$body = h(strip_tags($episode['description']));
	$subtitle = $this->Text->truncate($body, 235, [
		'ending' => '...',
		'exact' => true,
		'html' => true,
	]);
	$body = $this->Text->truncate($body, 400, [
		'ending' => '...',
		'exact' => true,
		'html' => true,
	]);

	echo $this->Rss->item([], [
		'title' => $episode['title'],

		'pubDate' => $this->Rss->time($episode['posted']),
		'guid' => [
			'isPermaLink' => 'true',
			'url' => $viewUrl,
		],
		'image' => [
			'attrib' => [
				'namespace' => 'itunes',
				'href' => $thumbnail,	//TODO: Episode-specific images
			],
		],
		'description' => [
			'attrib' => [],
			'cdata' => true,
			'value' => $body,
		],
		'enclosure' => [
			'length' => $episode['filesize'],
			'type' => 'audo/mpeg',
			'url' => $downloadUrl,
		],
		'duration' => [
			'attrib' => ['namespace' => 'itunes'],
			'value' => $episode['duration_format'],
		],
		'explicit' => [
			'attrib' => ['namespace' => 'itunes'],
			'value' => !empty($episode['explicit']) ? 'yes' : 'no',
		],

		'keywords' => [
			'attrib' => ['namespace' => 'itunes'],
			'value' => $episode['keywords'],
		],
		'subtitle' => [
			'attrib' => ['namespace' => 'itunes'],
			'cdata' => true,
			'value' => $subtitle,
		],
		'link' => $viewUrl,
	]);
endforeach;