<<<<<<< HEAD
<table>
<?php 
debug ($projects);
foreach($projects as $project):?>
	<tr>
		<td><?php echo $this->Html->link($project['Project']['title'], 
					['controller' => 'projects', 
							'action' => 'view',
							$project['Project']['id'],
							]);
			?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
=======
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
>>>>>>> a75684eae3ea004f762be9ecb4dfe7504196c8d3
