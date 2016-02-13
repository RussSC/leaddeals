<?php 
class AppSchema extends CakeSchema {

	public function before($event = []) {
		return true;
	}

	public function after($event = []) {
	}

	public $project_statuses = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 256, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1]
		],
		'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB']
	];

	public $projects = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 256, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'description' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'project_status_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'],
		'released' => ['type' => 'date', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'project_status_id' => ['column' => 'project_status_id', 'unique' => 0]
		],
		'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB']
	];

}
