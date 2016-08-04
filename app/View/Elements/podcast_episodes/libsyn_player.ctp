<?php
$default = [
	'embed' 		=> 'episode',
	'id' 			=> null,
	'height' 		=> 60,
	'width' 		=> 640,
	'theme' 		=> 'standard',
	'autonext' 		=> 'no',
	'thumbnail' 	=> false,
	'autoplay' 		=> 'no',
	'preload' 		=> 'no',
	'no_addthis' 	=> 'no',
	'direction'		=> 'backward',
	'no-cache' 		=> true,
];
$config = array_merge($default, compact(array_keys($default)));

$url = '//html5-player.libsyn.com/';
foreach ($config as $key => $val) {
	if ($val === true) {
		$val = 'yes';
	} else if ($val === false) {
		$val = 'no';
	}
	$url .= $key . '/' . $val . '/';
}

//ALTER TABLE `podcast_episodes` ADD COLUMN libsyn_id INT(11) NULL AFTER download_url;
?>

<iframe 
	style="border: none; width: 100%;" 
	src="<?php echo $url; ?>" 
	height="<?php echo $config['height']; ?>" 
	width="<?php echo $config['width']; ?>" 
	scrolling="no" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen
></iframe>






