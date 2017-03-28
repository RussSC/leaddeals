<?php
class Article extends AppModel {
	public $actsAs = [
		'FormData.Sluggable',
		'PublicConditions' => [
			'Article.active' => 1,
			'NOT' => ['Article.published' => NULL],
			'Article.published <= NOW()',
		],
		'Uploadable.ContainFieldUpload',
		'Uploadable.EmbedImages',
		'Uploadable.FieldUpload' => [
			'thumbnail' => [],
			'banner' => [],
		],
	];

	public $order = [
		'Article.published' => 'DESC', 
		'Article.created' => 'DESC'
	];

	public $belongsTo = ['User'];
	public $hasAndBelongsToMany = [
		'Podcast',
		'PodcastEpisode'
	];

	public function beforeFind($query = []) {
		$oQuery = $query;
		if (!empty($query['podcastId'])) {
			$db = $this->getDataSource();
			$subQuery = $db->buildStatement([
				'table' => 'articles',
				'alias' => 'Article',
				'fields' => ['Article.id AS article_id'],
				'joins' => [
					// Podcasts
					[
						'table' => 'articles_podcasts',
						'alias' => 'PodcastFilter',
						'type' => 'LEFT',
						'conditions' => 'PodcastFilter.article_id = ' . $this->escapeField(),
					],
					// Podcast Episodes
					[
						'table' => 'articles_podcast_episodes',
						'alias' => 'ArticlesPodcastEpisodeFilter',
						'type' => 'LEFT',
						'conditions' => 'ArticlesPodcastEpisodeFilter.article_id = ' . $this->escapeField(),
					], [
						'table' => 'podcast_episodes',
						'alias' => 'PodcastEpisodeFilter',
						'type' => 'LEFT',
						'conditions' => [
							'PodcastEpisodeFilter.id = ArticlesPodcastEpisodeFilter.podcast_episode_id',
						]
					]
				],
				'conditions' => [
					'OR' => [
						'PodcastEpisodeFilter.podcast_id' => $query['podcastId'],
						'PodcastFilter.podcast_id' => $query['podcastId'],
					]
				],
				'group' => 'article_id',
			], $this);

			$query['joins'][] = [
				'table' => "($subQuery)",
				'alias' => 'PodcastFilter',
				'conditions' => 'PodcastFilter.article_id = ' . $this->escapeField(),
			];
			unset($query['podcastId']);
		}

		if (!empty($query['podcastEpisodeId'])) {
			$db = $this->getDataSource();
			$subQuery = $db->buildStatement([
				'table' => 'articles',
				'alias' => 'Article',
				'fields' => ['Article.id AS article_id'],
				'joins' => [
					[
						'table' => 'articles_podcast_episodes',
						'alias' => 'ArticlesPodcastEpisodeFilter',
						'conditions' => 'ArticlesPodcastEpisodeFilter.article_id = ' . $this->escapeField(),
					]
				],
				'conditions' => [
					'ArticlesPodcastEpisodeFilter.podcast_episode_id' => $query['podcastEpisodeId'],
				],
				'group' => 'article_id',
			], $this);
			$query['joins'][] = [
				'table' => "($subQuery)",
				'alias' => 'PodcastEpisodeFilter',
				'conditions' => 'PodcastEpisodeFilter.article_id = ' . $this->escapeField(),
			];
			unset($query['podcastEpisodeId']);
		}


		if ($query != $oQuery) {
			return $query;
		}
		return parent::beforeFind($query);
	}

	// Permissions
	public function canAdd($userId) {
		return true;
	}

	public function canEdit($id, $userId) {
		return $this->isEditor($id, $userId) || $this->User->isAdmin($userId);
	}

	public function canDelete($id, $userId) {
		return $this->isEditor($id, $userId) || $this->User->isAdmin($userId);
	}
	// End Permissions

/**
 * Determines if a user is the editor of the article
 *
 * @param int $id The article id
 * @param int $userId The user id
 * @return bool True if the user is the editor
 **/
	public function isEditor($id, $userId) {
		return $this->find('count', [
			'recursive' => -1,
			'conditions' => [
				$this->escapeField() => $id,
				$this->escapeField('user_id') => $userId,
			]
		]);
	}

}