<?php
	echo $this->Form->create();
	echo $this->Form->hidden('Project.id');
	echo $this->Form->imput('Project.title', array(
			'label'=>'Project Title',
			'type'=>'text'));
	echo $this->Form->imput('Project.description', array(
			'label'=>'Project Description',
			'type'=>'textarea'));
	echo $this->Form->imput('Project.released', array(
			'label'=>'Project Released',
			'type'=>'date'));
	echo $this->Form->submit('Submit');
	echo $this->Form->end();