<?php

class ProjectStatus extends AppModel {
	public $name = 'ProjectStatus';
	public $hasMany = array('Project');
}