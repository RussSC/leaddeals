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
				<th class="text-center">Episodes</th>
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
				<td class="text-center">
					<span class="label label-default"><?php echo number_format($podcast['Podcast']['podcast_episode_count']); ?></span>
				</td>
				<td><?php echo $this->Time->niceShort($podcast['Podcast']['last_episode_posted']); ?></td>
				<td>
					<?php echo $this->Bootstrap->linkBtnGroup([
						['View', $url],
						['Edit', ['action' => 'edit'] + $url],
						['Delete', ['action' => 'delete'] + $url, ['postLink' => true, 'confirm' => 'Delete this Podcast?']],
						['Add', 
							[
								'controller' => 'podcast_episodes', 
								'action' => 'add', 
								$podcast['Podcast']['id']
							],
						]
					]); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paginate'); ?>
<?php endif; ?>
