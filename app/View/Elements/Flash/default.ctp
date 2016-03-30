<?php
if (empty($type)) {
	$type = 'info';
}

$class = $type;
if ($class == 'error') {
	$class = 'danger';
}
?>
<div class="alert alert-<?php echo $class; ?>">
	<?php echo $message; ?>
</div>