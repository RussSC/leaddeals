<?php
echo $this->Layout->defaultHeader();
$this->Table->reset();
	
foreach($projects as $project):	
	$this->Table->cell(
		$this->Html->link($project['Project']['title'],	array(
			'controller' => 'projects', 
			'action' => 'view',
			$project['Project']['id'],
			)),
		'Project Name');
	$this->Table->cell(
		$this->ModelView->actionMenu(['view', 'edit', 'delete'], 
					$project['Project']),
		'Actions');
	$this->Table->rowEnd();
endforeach;
echo $this->Table->output();
?>