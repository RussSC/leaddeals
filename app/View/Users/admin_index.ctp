<?= $this->Html->link('Add User', ['action' => 'add']); ?>

<?php
$this->Table->reset();
foreach ($users as $user):
	$url = [
		'action' => 'view',
		$user['User']['id'],
	];
	$this->Table->checkbox($user['User']['id']);
	$this->Table->cells([
		[
			$this->Html->link(
				$user['User']['name'],
				$url
			),
			'Name',
		],
		[
			$this->Html->link('Edit', ['action' => 'edit'] + $url) .
			$this->Html->link('Delete', ['action' => 'delete'] + $url)
			,
			'Actions'
		]
	], true);
endforeach;
echo $this->Table->output();