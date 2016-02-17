<?php
$this->Table->reset();
foreach ($podcastEpisodes as $podcastEpisode):
	$url = ['action' => 'view', $podcastEpisode['PodcastEpisode']['id']];
	$actions = $this->Html->link('View', $url);
	$actions .= $this->Html->link('Edit', ['action' => 'edit'] + $url);
	$actions .= $this->Form->postLink('Delete', ['action' => 'delete'] + $url, null, 'Delete this episode?');

	$isActive = $podcastEpisode['PodcastEpisode']['active'];

	$class = $isActive ? 'active' : 'inactive';

	$this->Table->cells([
		[
			$this->Html->link(
				$podcastEpisode['PodcastEpisode']['title'],
				$url
			),
			'Title',
		], [
			$podcastEpisode['PodcastEpisode']['episode_number'],
			'#',
		], [
			$this->Html->link(
				$podcastEpisode['Podcast']['title'],
				['controller' => 'podcasts', 'action' => 'view', $podcastEpisode['Podcast']['id']]
			),
			'Podcast',
		], [
			$this->Html->tag('span',
				$isActive ? 'Yes' : 'No', [
					'class' => 'label ' . ($isActive ? 'label-success' : 'label-default')
				]
			),
			'Active',
		], [
			number_format($podcastEpisode['PodcastEpisode']['download_count']),
			'Downloads',
		], [
			$actions,
			'Actions',
		]
	], compact('class'));
endforeach;
echo $this->Table->output(['paginate' => true]);