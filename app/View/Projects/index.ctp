<h1>Projects</h1>
<?php
	echo $this->Html->link('Add', array(
			'controller'=>'projects',
			'action'=>'add'));
		?>
<table>
<?php 
foreach($projects as $project):?>
	<tr>
		<td><?php echo $this->Html->link($project['Project']['title'], 
					array('controller' => 'projects', 
							'action' => 'view',
							$project['Project']['id'],
							));
			?>
		</td>
		<td><?php echo $this->Html->link('Edit', 
					array('controller' => 'projects', 
							'action' => 'edit',
							$project['Project']['id'],
							));?>
		</td>
		<td><?php echo $this->Html->link(
			'Delete', 
			array(
				'controller' => 'projects', 
				'action' => 'delete',
				$project['Project']['id'],
			),
			null,
			'Are you sure you wish to delete this?');?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
