<?php
	echo $this->Form->create();
	echo $this->Form->hidden('Project.id');
	echo $this->Form->input('Project.title', array(
			'label'=>'Project Title',
			'type'=>'text'));
	echo $this->Form->input('Project.description', array(
			'label'=>'Project Description',
			'type'=>'textarea'));
	echo $this->FormLayout->inputDate('Project.released', array(
			'label'=>'Project Released',
			));
	echo $this->Form->submit('Submit');
	echo $this->Form->end();