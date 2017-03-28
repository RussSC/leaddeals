<?php
class PodcastEpisode extends AppModel {
	public $name = 'PodcastEpisode';
	public $actsAs = [
		'Uploadable.ContainFieldUpload',
		'Uploadable.FieldUpload' => [
			'banner' => [],
			'thumbnail' => []
		],
		'PublicConditions'
	];

	public $order = ['PodcastEpisode.created' => 'DESC'];
	
	public $hasMany = ['PodcastEpisodeDownload'];
	public $belongsTo = [
		'Podcast' => [
			'counterCache' => 'podcast_episode_count',
		]
	];

	public $hasAndBelongsToMany = ['User', 'Article'];

	public $validate = [
		'title' => [
			'rule' => 'notBlank',
			'message' => 'Please enter an episode title',
		],
		'episode_number' => [
			'rule' => 'notBlank',
			'message' => 'Please enter an episode number',
		],
		'published' => [
			'rule' => 'notBlank',
			'message' => 'Please pick a date when this should post',
		],
		'duration_hh' => [
			'rule' => 'notBlank',
			'message' => 'Please enter a duration',
		],
		'duration_mm' => [
			'rule' => 'notBlank',
			'message' => 'Please enter a duration',
		],
		'duration_ss' => [
			'rule' => 'notBlank',
			'message' => 'Please enter a duration',
		],
	];

	public function afterSave($created, $options = []) {
		$id = $this->id;
		$this->setTitle($id);
		$this->setDuration($id);
		$this->setFilesize($id);
		return parent::afterSave($created, $options);
	}

	public function beforeFind($query = []) {
		$oQuery = $query;

		if (!empty($query['hasUser'])) {
			$query['joins'][] = [
				'type' => 'LEFT',
				'table' => 'podcast_episodes_users',
				'alias' => 'PodcastEpisodesUserLink',
				'conditions' => 'PodcastEpisodesUserLink.podcast_episode_id = ' . $this->escapeField(),
			];
			$query['joins'][] = [
				'type' => 'LEFT',
				'table' => 'podcasts',
				'alias' => 'PodcastLink',
				'conditions' => 'PodcastLink.id = ' . $this->escapeField('podcast_id'),
			];
			$query['joins'][] = [
				'type' => 'LEFT',
				'table' => 'podcasts_users',
				'alias' => 'PodcastUserLink',
				'conditions' => 'PodcastUserLink.podcast_id = PodcastLink.id',
			];
			$query['conditions'][]['OR'] = [
				'PodcastEpisodesUserLink.user_id' => $query['hasUser'],
				'PodcastUserLink.user_id' => $query['hasUser']
			];
			$query['group'] = $this->escapeField();
			unset($query['hasUser']);
		}

		if ($oQuery != $query) {
			return $query;
		}
		return parent::beforeFind($query);
	}

	public function afterFind($results, $primary = false) {
		if (isset($results[0]['PodcastEpisode'])) {
			foreach ($results as $k => $row) {
				if (!empty($row['Podcast'])) {
					$results[$k]['PodcastEpisode'] = $this->copyUploadable($row['PodcastEpisode'], $row['Podcast']);
				}
			}
		} else if (isset($results['PodcastEpisode']) && isset($results['Podcast'])) {
			$results['PodcastEpisode'] = $this->copyUploadable($results['PodcastEpisode'], $results['Podcast']);
		}
		return parent::afterFind($results, $primary);
	}

	private function copyUploadable($result, $podcastResult) {
		if (!empty($podcastResult)) {
			if (!empty($podcastResult['Podcast']['uploadable'])) {
				$podcastResult = $podcastResult['Podcast'];
			}
		}
		if (!empty($result['uploadable'])) {
			foreach ($result['uploadable'] as $field => $config) {
				foreach ($config['sizes'] as $size => $sizeConfig) {
					if (empty($sizeConfig) && !empty($podcastResult['uploadable'][$field]['sizes'][$size])) {
						$result['uploadable'][$field]['sizes'][$size] = $podcastResult['uploadable'][$field]['sizes'][$size];
					}
				}
			}
		}
		return $result;
	}

	public function findNeighbors($id, $query = []) {
		$currentQuery = $query;
		$currentQuery['conditions'][$this->escapeField()] = $id;
		$result = $this->find('first', $currentQuery);
		if (empty($result)) {
			return null;
		}

		$result = $result[$this->alias];
		$return = [];

		$query['conditions'][$this->escapeField('podcast_id')] = $result['podcast_id'];

		// Find Previous
		$prevQuery = $query;
		$prevQuery['conditions'][$this->escapeField() . ' <'] = $result['id'];
		$prevQuery['order'] = [$this->escapeField() => 'DESC'];
		$return['prev'] = $this->find('first', $prevQuery);

		// Find Next
		$nextQuery = $query;
		$nextQuery['conditions'][$this->escapeField() . ' >'] = $result['id'];
		$nextQuery['order'] = [$this->escapeField() => 'ASC'];
		$return['next'] = $this->find('first', $nextQuery);

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

		$episodeNumber = $result['PodcastEpisode']['episode_number'];
		if ($episodeNumber == round($episodeNumber)) {
			$episodeNumber = round($episodeNumber);
		}

		$data = compact('id');
		$data['full_title'] = sprintf('%s Episode #%s: %s',
			$result['Podcast']['title'],
			$episodeNumber,
			$result['PodcastEpisode']['title']
		);
		$data['numeric_title'] = sprintf('%s: %s', 
			$episodeNumber,
			$result['PodcastEpisode']['title']
		);
		
		$data['slug'] = Inflector::slug(sprintf('%s Episode %s"',
			$result['Podcast']['title'],
			$episodeNumber
		));

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
		$conditions[$this->escapeField('published') . ' <='] = date('Y-m-d H:i:s');
		$conditions[]['NOT'][$this->escapeField('download_url')] = '';
		return $conditions;
	}	
}
