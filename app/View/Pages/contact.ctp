<?php
$this->layout = 'CenteredContent.default';
$this->set('centeredContent', [
	'pageTitle' => 'Contact Us',
]);

$info = [
	'General' => 'info@lead-deals.com',
	'Podcasts' => 'podcasts@lead-deals.com',
	'Facebook' => 'https://www.facebook.com/Lead-Deals-Productions-111002299065/',
	'Twitter' => 'https://twitter.com/leaddeals',
];
?>

<dl class="dl-horizontal">
	<?php foreach ($info as $dt => $dd): ?>
		<dt><?php echo $dt; ?></dt>
		<dd>
		<?php if (!is_array($dd)):
			$dd = [$dd];
		endif;
		foreach ($dd as $link):
			$url = $link;
			if (strpos($url, '@')) {
				$url = 'mailto:' . $url;
			} else {
				$link = '[Link]';
			}
			echo $this->Html->link($link, $url);
		endforeach; ?>
		</dd>
	<?php endforeach; ?>
</dl>