<?php
class ArticlesController extends AppController {
	public $components = [
		'ResultFilter.ResultFilter'
	];

	public $helpers = [
		'Layout.DisplayText',
		'Podcast', 
		'Uploadable.FieldUploadImage',
		'Uploadable.EmbeddedImage',
	];

	public function beforeFilter($options = []) {
		parent::beforeFilter($options);
		$this->Auth->deny(['add', 'edit']);
	}

	public function index() {
		$this->ResultFilter->set([
			'podcast_id' => [
				'label' => 'Podcast',
				'default' => null,
			],
			'podcast_episode_id' => [
				'label' => 'Podcast Episode',
				'default' => null,
			],
		]);

		$this->paginate = $this->ResultFilter->filter(['public' => 1]);

		$articles = $this->paginate();
		$this->set(compact('articles'));
		$this->set('isAdmin', $this->Article->User->isAdmin($this->Auth->user('id')));
	}

	public function add() {
		$this->Crud->create();
	}

	public function view($id = null, $slug = null) {
		$article = $this->Crud->read($id, [
			'query' => [
				'contain' => [
					'Podcast' => [
						'conditions' => [
							'Podcast.active' => 1,
						],
					],
					'PodcastEpisode' => [
						'Podcast',
						'conditions' => [
							'PodcastEpisode.active' => 1,
						],
					],
					'User' => ['UserLink'],
					'EmbeddedImage',
				],
				//'cache' => true,
			]
		]);

		if (empty($slug) && !empty($article['Article']['slug'])) {
			$this->redirect(['id' => $id, 'slug' => $article['Article']['slug']]);
		}

		$this->set('title_for_layout', 'Article: ' . $article['Article']['title']);
		$this->set([
			'isAdmin' => $this->Article->User->isAdmin($this->Auth->user('id')),
			'canEdit' => $this->Article->canEdit($id, $this->Auth->user('id')),
			'canDelete' => $this->Article->canDelete($id, $this->Auth->user('id')),
		]);
	}

	public function edit($id = null) {
		$this->Crud->update($id);
	}

	public function delete($id = null) {
		if ($this->request->is('post')) {
			$this->Crud->delete($id);
		}
	}

	public function _setResultFilterValue($key, $value, $query = []) {
		//print_r(compact('key', 'value'));
		if ($key == 'podcast_id' && !empty($value)) {
			$query['podcastId'] = $value;
		} else if ($key == 'podcast_episode_id' && !empty($value)) {
			$query['podcastEpisodeId'] = $value;
		}
		return $query;
	}

	public function _setResultFilterDisplay($key, $value) {
		if ($key == 'podcast_id') {
			$result = $this->Article->Podcast->read('title', $value);
			return $result['Podcast']['title'];
		}
		if ($key == 'podcast_episode_id') {
			$result = $this->Article->PodcastEpisode->read('title', $value);
			return $result['PodcastEpisode']['title'];
		}
		return $value;
	}
	
	public function _getFormDefaults() {
		$default = [
			'Article' => [
				'user_id' => $this->Auth->user('id'),
				'active' => 0,
				'published' => date('Y-m-d H:i:s')
			]
		];
		if (!empty($this->request->named['podcast_id'])) {
			//$default['ArticlesPodcast'][0]['podcast_id'] = $this->request->named['podcast_id'];
		}

		if (!empty($this->request->named['podcast_episode_id'])) {
			//$default['ArticlesPodcastEpisode'][0]['podcast_episode_id'] = $this->request->named['podcast_episode_id'];
			$default['PodcastEpisode'][] = [
				'id' => $this->request->named['podcast_episode_id']
			];
		}
		return $default;
	}

	public function _setFormElements() {
		$userId = $this->Auth->user('id');

		$isAdmin = $this->Article->User->isAdmin($userId);
		$podcasts = $this->Article->Podcast->find('list', [
			'hasUser' => $userId,
		]);
		$podcastEpisodes = $this->Article->PodcastEpisode->find('list', [
			'fields' => ['PodcastEpisode.id', 'PodcastEpisode.full_title'],
			'hasUser' => $userId,
		]);

		if (!empty($this->request->named['podcast_id'])) {
			$podcastId = $this->request->named['podcast_id'];
			if (!empty($podcasts[$podcastId])) {
				$podcast = $this->Article->Podcast->read('title', $podcastId);
				$podcasts[$podcastId] = $podcast['Podcast']['title'];
			}
		}

		if (!empty($this->request->named['podcast_episode_id'])) {
			$podcastEpisodeId = $this->request->named['podcast_episode_id'];
			if (!empty($podcastEpisodes[$podcastEpisodeId])) {
				$podcastEpisode = $this->Article->PodcastEpisode->read('full_title', $podcastEpisodeId);
				$podcastEpisodes[$podcastEpisodeId] = $podcastEpisode['PodcastEpisode']['title'];
			}
		}

		$this->set(compact('podcasts', 'podcastEpisodes'));
	}

	public function isAuthorized($user) {
		$id = null;
		if (!empty($this->request->pass[0])) {
			$id = $this->request->pass[0];
		}
		$userId = $user['id'];

		switch ($this->request->action) {
			case 'add':
				return $this->Article->canAdd($userId);
			case 'edit':
				return $this->Article->canEdit($id, $userId);
			case 'delete':
				return $this->Article->canDelete($id, $userId);
		}

		return parent::isAuthorized($user);
	}
}