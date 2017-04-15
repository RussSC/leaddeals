<?php
$linkList = [];
foreach ($users as $user):
	$linkList[] = $this->Html->link(
		$user['name'], 
		['controller' => 'users', 'action' => 'view', $user['id']]											
	);
endforeach; 
?>
<div class="user-list">
	<?php if (!empty($title)): ?>
		<strong><?= $title ?>:</strong>
	<?php endif; ?>
	<?= implode(', ', $linkList) ?>
</div>
