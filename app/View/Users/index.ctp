<?php foreach ($users as $user):
	$url = ['controller' => 'users', 'action' => 'view', $user['User']['id']];
	$this->Table->cells([
		[
			$this->FieldUploadImage->image($user['User'], 'thumbnail', 'thumbnail-sm', compact('url')),
		], [
			$this->Html->link($user['User']['name'], $url),
			'Name',
		],
	], true);
endforeach;
echo $this->Table->output(['paginate' => true]);