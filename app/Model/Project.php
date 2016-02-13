<?php

class Project extends AppModel {
	public $name = 'Project';
	public $belongsTo = ['ProjectStatus'];
}