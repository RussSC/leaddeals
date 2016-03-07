<?php
App::uses('Inflector', 'Utility');

class Podcast extends AppModel {
	public $name = 'Podcast';

	public $actsAs = [
		'Uploadable.FieldUpload' => [
			'thumbnail' => [],
			'banner' => [],
		],
		'PublicConditions'
	];

	public $hasMany = [
		'PodcastsUser',
		'PodcastEpisode' => [
			'dependent' => true,
		]
	];

	public $hasAndBelongsToMany = ['User'];

	public $validate = [
		'title' => [
			'rule' => 'notEmpty',
			'message' => 'Please give this podcast a title',
		],
	];
	
	public function beforeFind($query) {
		$oQuery = $query;
		if (!empty($query['hasUser'])) {
			$query['joins'][] = [
				'table' => 'podcasts_users',
				'alias' => 'PodcastsUserLink',
				'conditions' => 'PodcastsUserLink.podcast_id = ' . $this->escapeField(),
			];
			$query['conditions']['PodcastsUserLink.user_id'] = $query['hasUser'];
			unset($query['hasUser']);
		}

		if ($oQuery != $query) {
			return $query;
		}
		return parent::beforeFind($query);
	}

	public function afterSave($created, $options = []) {
		$id = $this->id;
		$this->updateSlug($id);
		return parent::afterSave($created, $options);
	}

	protected function updateSlug($id, $slug = null) {
		$slug = $this->getSlug($id, $slug);
		return $this->save(compact('id', 'slug'), ['callbacks' => false]);
	}

	public function getNewEpisodeNumber($id) {
		$episodeCount = $this->PodcastEpisode->find('count', [
			'conditions' => ['PodcastEpisode.podcast_id' => $id]
		]);
		return $episodeCount + 1;
	}

	public function isEditor($id, $userId) {
		if ($this->User->isAdmin($userId)) {
			return true;
		}
		$result = $this->find('first', [
			'contain' => ['PodcastsUser' => [
				'conditions' => ['PodcastsUser.user_id' => $userId],
			]],
			'conditions' => [
				$this->escapeField() => $id,
			]
		]);
		return !empty($result['PodcastsUser']);
	}

/**
 * Gets a slug of the podcast
 *
 * @param int $id The podcast ID
 * @param string $slug A custom slug
 * @return string;
 **/
	protected function getSlug($id, $slug = null) {
		$result = $this->read(['auto_slug', 'title', 'slug'], $id);
		$result = $result[$this->alias];
		if (!empty($result['auto_slug'])) {
			$slug = $result['title'];
		} else if (empty($slug)) {
			$slug = $result['slug'];
		}
		return Inflector::slug($slug);
	}

	public function publicConditions($conditions) {
		$conditions[$this->escapeField('active')] = 1;
		return $conditions;
	}
}