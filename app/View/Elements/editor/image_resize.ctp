<?php
$default = [
	'result' => [],
	'model' => null,
	'field' => null,
	'sizes' => [],
	'id' => null,
];

extract(array_merge($default, compact(array_keys($default))));

foreach ($sizes as $config): 
	if (!is_array($config)) {
		$config = ['size' => $config];
	}
	foreach ($default as $key => $val) {
		if (empty($config[$key]) && !empty($$key)) {
			$config[$key] = $$key;
		}
	}

	?>
	<div class="clearfix">
		<h4><?php echo $this->FieldUploadImage->resizeLink(
			!empty($config['title']) ? $config['title'] : $config['size'],
			$config['model'],
			$config['id'],
			$config['field'],
			$config['size']
		); ?></h4>
		<?php
		echo $this->FieldUploadImage->resizeLink(
			$this->FieldUploadImage->image(
				$config['result'], 
				$config['field'], 
				$config['size'], [
					'style' => 'max-width: 100%',
				]
			),
			$config['model'],
			$config['id'],
			$config['field'],
			$config['size'], 
			['escape' => false]
		);
		?>
	</div>
<?php endforeach; ?>
