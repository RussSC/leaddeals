<?php $this->extend('admin'); ?>
<div class="layout-header layout-header-admin">
	<h2 class="layout-header-title">Edit <?php echo $layoutData['modelHuman']; ?></h2>
</div>

<?php echo $this->fetch('content'); ?>