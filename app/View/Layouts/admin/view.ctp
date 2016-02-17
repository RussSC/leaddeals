<?php
$this->extend('admin');
if (!empty($layoutData)):
	extract($layoutData);
	$title = $result[$modelName][$displayField];
	$id = $result[$modelName][$primaryKey];
	?>
	<div class="content-layout-header content-layout-header-admin">
		<h2 class="content-layout-header-title"><?php echo $result[$modelName][$displayField]; ?></h2>
		<div class="content-layout-header-nav nav nav-pills">
			<?php echo $this->Html->link(
				'Edit',
				['controller' => $controller, 'action' => 'edit', $id]
			); ?>

			<?php echo $this->Html->link(
				'Delete',
				compact('controller') + ['action' => 'delete', $id],
				null,
				'Are you sure you want to delete this ' . $modelHuman . '?'
			); ?>
		</div>
	</div>
<?php endif; ?>

<?php echo $this->fetch('content'); ?>