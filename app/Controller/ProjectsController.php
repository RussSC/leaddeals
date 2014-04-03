<?php

class ProjectsController extends AppController {
	public $name = 'Projects';
	public $helpers = array('Time');
	public function index(){
		$projects = $this->Project->find('all');
		$this->set('projects', $projects);
			}
	public function view($id=null){
		$project = $this->Project->find('first',
		array(
			'conditions' => array(
				'Project.id' => $id))
		);
		$this->set('project', $project);
	}
	public function edit($id=null){
		if (!empty($this->request->data)){
			$success = $this->Project->save($this->request->data['Project']);
			if ($success) {
				$this->Session->setFlash('It worked!');
					$this->redirect(array(
						'controller'=>'projects', 
						'action'=>'view',
						$this->request->data['Project']['id']));
			}
		} else {
			$this->request->data = $this->Project->find('first', 
				array('conditions'=>array('Project.id'=>$id)));
			}
	}
	public function add() {
		if (!empty($this->request->data)){
			$success = $this->Project->save($this->request->data['Project']);
			if ($success) {
				$this->Session->setFlash('It worked!');
					$this->redirect(array(
						'controller'=>'projects', 
						'action'=>'view',
						$this->Project->id));
			}
		} 
	}
	public function delete($id=null) {
		$success = $this->Project->delete($id);
		if ($success) {
			$this->Session->setFlash('Deleted');
		}
		else {
			$this->Session->setFlash('Delete Failed');
		}
		$this->redirect(
			array(
				'controller'=>'projects',
				'action'=>'index'
			));
	}
}