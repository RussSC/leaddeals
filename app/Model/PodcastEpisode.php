<?php
class PodcastEpisode extends AppModel {
	public $name = 'PodcastEpisode';
	public $actsAs = [
		'Uploadable.ContainFieldUpload',
		'Uploadable.FieldUpload' => [
			'banner' => [],
		],
		'PublicConditions'
	];
	public $hasMany = ['PodcastEpisodeDownload'];
	public $belongsTo = [
		'Podcast' => [
			'counterCache' => 'podcast_episode_count',
		]
	];

	public $hasAndBelongsToMany = ['User'];

	public $validate = [
		'title' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter an episode title',
		],
		'episode_number' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter an episode number',
		],
		'posted' => [
			'rule' => 'notEmpty',
			'message' => 'Please pick a date when this should post',
		],
		'duration_hh' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter a duration',
		],
		'duration_mm' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter a duration',
		],
		'duration_ss' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter a duration',
		],
		'filesize' => [
			'rule' => 'notEmpty',
			'message' => 'Please enter a file size for the MP3',
		],
	];

	public function afterSave($created, $options = []) {
		$id = $this->id;
		$this->setTitle($id);
		$this->setDuration($id);
		$this->setFilesize($id);
		return parent::afterSave($created, $options);
	}

	public function findNeighbors($id, $query = []) {
		$query['fields'] = '*';
		$query['joins'][] = [
			'table' => 'podcast_episodes',
			'alias' => 'NextEpisode',
			'type' => 'LEFT',
			'conditions' => [
				'NextEpisode.podcast_id = ' . $this->escapeField('podcast_id'),
				'NextEpisode.episode_number = ' . $this->escapeField('episode_number') . ' + 1',
			]
		];
		$query['joins'][] =  [
			'table' => 'podcast_episodes',
			'alias' => 'PrevEpisode',
			'type' => 'LEFT',
			'conditions' => [
				'PrevEpisode.podcast_id = ' . $this->escapeField('podcast_id'),
				'PrevEpisode.episode_number = ' . $this->escapeField('episode_number') . ' - 1',
			]
		];
		$query['conditions'][$this->escapeField()] = $id;
		$result = $this->find('first', $query);

		$return = [];
		foreach (['prev' => 'PrevEpisode', 'next' => 'NextEpisode'] as $key => $field) {
			$return[$key] = !empty($result[$field]['id']) ? [$this->alias => $result[$field]] : null;
		}
		return $return;
	}

	public function isEditor($id, $userId) {
		$result = $this->read('podcast_id', $id);
		return $this->Podcast->isEditor($result['PodcastEpisode']['podcast_id'], $userId);
	}
/**
 * Records a download of a podcast episode
 *
 * @param int $id The podcast episode id
 * @param string $p The IP address of the download
 * @return bool;
 **/
	public function setDownloaded($id, $ip = null) {
		return $this->PodcastEpisodeDownload->save([
			'podcast_episode_id' => $id,
			'ip' => $ip,
		]);
	}

/**
 * Uses the individual components of an episode's duration to calculate the full length in minutes
 *
 * @param int $id The episode id
 * @return bool;
 **/
	protected function setDuration($id) {
		$data = compact('id');
		$result = $this->read(['duration_hh', 'duration_mm', 'duration_ss'], $id);
		$result = $result[$this->alias];
		$data['duration'] = $result['duration_mm'] + 60 * $result['duration_hh'] + $result['duration_ss'] / 60;
		$data['duration_format'] = sprintf('%02d:%02d:%02d', $result['duration_hh'], $result['duration_mm'], $result['duration_ss']);
		return $this->save($data, ['callbacks' => false]);
	}

/**
 * Updates the various titles after the regular title is updated
 *
 * @param int $id The podcast episode number
 * @return bool;
 **/
	protected function setTitle($id) {
		$result = $this->find('first', [
			'contain' => ['Podcast'],
			'conditions' => [
				$this->escapeField() => $id,
			]
		]);

		$data = compact('id');
		$data['full_title'] = sprintf('%s Episode #%d: "%s"',
			$result['Podcast']['title'],
			$result['PodcastEpisode']['episode_number'],
			$result['PodcastEpisode']['title']
		);
		$data['numeric_title'] = sprintf('%d: %s', 
			$result['PodcastEpisode']['episode_number'],
			$result['PodcastEpisode']['title']
		);
		return $this->save($data, ['callbacks' => false]);
	}

	protected function setFilesize($id) {
		return $this->save([
			'id' => $id,
			'filesize' => $this->getFilesize($id),
		], ['callbacks' => false, 'validate' => false]);
	}

	protected function getFilesize($id) {
		$result = $this->read('download_url', $id);
		$url = $result[$this->alias]['download_url'];
		$size = 0;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);
		curl_close($ch);
		if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
			// Contains file size in bytes
			$size = (int)$matches[1];
		}
		return $size;
	}

	public function publicQueryData($query) {
		$query['joins'][] = [
			'table' => 'podcasts',
			'alias' => 'PublicPodcast',
			'conditions' => [
				'PublicPodcast.id = ' . $this->escapeField('podcast_id'),
				'PublicPodcast.active' => 1,
			]
		];
		return $query;
	}

	public function publicConditions($conditions) {
		$conditions[$this->escapeField('active')] = 1;
		$conditions[$this->escapeField('posted') . ' <='] = date('Y-m-d H:i:s');
		$conditions[]['NOT'][$this->escapeField('download_url')] = '';
		return $conditions;
	}	
}