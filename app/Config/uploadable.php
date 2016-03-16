<?php
$config['Uploadable'] = [
	// Default thumbnail sizes
	'sizes' => [
		'full' => [],
		'thumbnail' => ['set' => [80,80]],
		'thumbnail-sm' => ['set' => [40, 40]],
		'thumbnail-md' => ['set' => [240, 240]],
		'thumbnail-lg' => ['set' => [360, 360]],
		'thumbnail-full' => ['square' => true, 'set' => [1800, 1800]],

		'banner' => ['set' => [1200,400]],
		'banner-share' => ['set' => [1200,630]],
		'small' => ['setSoft' => [80,80]],
		'mid' => ['max' => [120,240]],
		'tiny' => ['set' => [20,20]]
	]
];