<table>
<?php 
debug ($projects);
foreach($projects as $project):?>
	<tr>
		<td><?php echo $this->Html->link($project['Project']['title'], 
					array('controller' => 'projects', 
							'action' => 'view',
							$project['Project']['id'],
							));
			?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
