<?php
$this->Html->css('ResultFilter.heading', null, ['inline' => false]);
?>
<?php if (!empty($resultFilter)): ?>
	<ul class="resultfilter-heading">
		<?php foreach ($resultFilter as $filter): 
			if (!array_key_exists('value', $filter)) {
				continue;
			}

			?>
			<li class="resultfilter-heading-item">
				<strong><?php echo $filter['label']; ?></strong>
				<?php echo $filter['displayValue']; ?>
				<?php echo $this->Html->link(
					'&times;', 
					['ResultFilter-remove' => $filter['key']] + $resultFilterUrl,
					[
						'class' => 'resultfilter-heading-item-close',
						'escape' => false,
					]
				); ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
