<h2><?php echo $project['Project']['title'];?></h2>
<h6><?php echo $this->Html->link('Edit', array(
				'controller'=>'projects', 
				'action'=>'edit',
				$project['Project']['id']
			));?></h6>
<hr />
<p><?php echo $project['Project']['description'];?></p>
<h6><?php echo $this->Time->niceShort(
	$project['Project']['released']);?></h6>