<?php if (empty($podcasts)): ?>
	<div class="empty-content">
		No podcasts found
	</div>
<?php else: ?>
	<?php echo $this->element('paginate'); ?>
	<table class="table">
		<thead>
			<tr>
				<th>Title</th>
				<th>Episodes</th>
				<th>Last Posted</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($podcasts as $podcast): 
			$url = ['controller' => 'podcasts', 'action' => 'view', $podcast['Podcast']['id']];
			?>
			<tr>
				<td><?php echo $this->Html->link(
					$podcast['Podcast']['title'],
					$url
				); ?></td>
				<td><?php echo number_format($podcast['Podcast']['podcast_episode_count']); ?></td>
				<td><?php echo $this->Time->niceShort($podcast['Podcast']['last_episode_posted']); ?></td>
				<td>
					<?php echo $this->Html->link('View', $url); ?>
					<?php echo $this->Html->link('Edit', ['action' => 'edit'] + $url); ?>
					<?php echo $this->Form->postLink('Delete', ['action' => 'delete'] + $url); ?>
					<?php echo $this->Html->link('Add', [
						'controller' => 'podcast_episodes', 
						'action' => 'add', 
						$podcast['Podcast']['id']
					]); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paginate'); ?>
<?php endif; ?>
