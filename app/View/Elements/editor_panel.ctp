<?php
$iconReplace = [
	'view' => '<i class="fa fa-search"></i>',
	'edit' => '<i class="fa fa-edit"></i>',
	'delete' => '<i class="fa fa-times"></i>',
	'index' => '<i class="fa fa-list"></i>',
	'add' => '<i class="fa fa-plus"></i>',
];

$default = [
	'controller' => $this->request->params['controller'],
	'title' => 'Editor',
	'actions' => [],
	'prefix' => null,
	'id' => null,
];
if (!empty($this->request->params['pass'][0])) {
	$default['id'] = $this->request->params['pass'][0];
}

$btnClass = 'btn btn-default btn-sm';
extract(array_merge($default, compact(array_keys($default))));

?>

<div class="panel panel-admin">
	<div class="panel-heading">
		<div class="pull-right">
			<?php foreach ($actions as $action => $config):
				if (is_numeric($action)) {
					$action = $config;
					$config = [];
				}
				$title = !empty($iconReplace[$action]) ? $iconReplace[$action] : $action;
				$url = compact('controller', 'action') + [$id];
				if (!empty($config['prefix'])){
					$url[$config['prefix']] = true;
				} else if (!empty($prefix)) {
					$url[$prefix] = true;
				}
				$options = ['escape' => false, 'class' => $btnClass];
				$onClick = $action == 'delete' ? 'Delete this?' : null;
				echo $this->Html->link($title, $url, $options, $onClick);
			endforeach; 
			if (!empty($links)):
				foreach ($links as $link):
					list($title, $url, $options, $onClick) = $link + [null, [], [], null];
					$title = !empty($iconReplace[$title]) ? $iconReplace[$title] : $title;
					$options = $this->Html->addClass($options, $btnClass);
					$options['escape'] = false;
					echo $this->Html->link($title, $url, $options, $onClick);
				endforeach;
			endif; ?>
		</div>
		<h2 class="panel-title">
			Editor
		</h2>
	</div>
</div>