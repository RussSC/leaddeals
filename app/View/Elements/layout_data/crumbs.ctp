<?php
$crumbs = [];
if (empty($layoutData)) {
	return '';
}

// Turn off default crumbs by setting $defaultCrumbs to false
if (isset($defaultCrumbs) && $defaultCrumbs === false) {
	return '';
}

extract($layoutData);
$action = $this->request->params['action'];
$prefix = null;
if (!empty($this->request->params['prefix'])) {
	$prefix = $this->request->params['prefix'];
	$action = substr($action, strlen($prefix) + 1);
}

if (!empty($modelHuman)) {
	$crumbs[] = [
		$modelHuman,
		compact('controller') + ['action' => 'index'],
	];
	if ($action != 'index') {
		if (!empty($result)) {
			$crumbs[] = [
				$result[$modelName][$displayField],
				compact('controller') + ['action' => 'view', $result[$modelName][$primaryKey]],
			];
		}
		if ($action != 'view') {
			$url = compact('controller', 'action');
			if (!empty($result[$modelName][$primaryKey])) {
				$url[] = $result[$modelName][$primaryKey];
			}
			$crumbs[] = [$action, $url];
		}
	}
}

$crumbItems = count($crumbs);
foreach ($crumbs as $k => $crumb) {
	if ($k + 1 == $crumbItems) {
		$this->Html->addCrumb($crumb[0]);
	} else {
		$this->Html->addCrumb($crumb[0], $crumb[1]);
	}
}