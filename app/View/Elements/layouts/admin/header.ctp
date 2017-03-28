<?php 
if (!empty($layoutData)):
	extract($layoutData);
	$title = $result[$modelName][$displayField];
	$id = $result[$modelName][$primaryKey];
	?>
	<header>
		<div class="content-layout-header content-layout-header-admin">
			<h2 class="content-layout-header-title"><?php echo $result[$modelName][$displayField]; ?></h2>
			<div class="content-layout-header-nav">
				<?php echo $this->Bootstrap->linkBtnGroup([
					[
						'Edit',
						['controller' => $controller, 'action' => 'edit', $id]
					], [
						'Delete',
						compact('controller') + ['action' => 'delete', $id],
						[
							'confirm' => 'Are you sure you want to delete this ' . $modelHuman,
						]
					]
				]); ?>
			</div>
		</div>
	</header>
	<hr/>
<?php endif; ?>