<?php

class ProjectsController extends AppController {
	public $name = 'Projects';
	public $helpers = ['Html', 'Time'];
	
	public function index(){
		$projects = $this->Project->find('all');
		$this->set('projects', $projects);
	}

	public function view($id=null){
		$project = $this->Project->find('first', [
			'conditions' => [
				'Project.id' => $id
			]
		]);
		
		$this->set('project', $project);
	}
}