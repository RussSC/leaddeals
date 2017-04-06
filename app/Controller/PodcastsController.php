<?php 
class PodcastsController extends AppController {
	public $name = 'Podcasts';
	public $components = ['FormData.Crud'];
	public $helpers = [
		'Rss' => [
			'className' => 'AppRss',
		], 
		'Uploadable.FieldUploadImage',
		'ShareLink',
		'Time',
		'Layout.DisplayText',
	];

	//public $layout = 'default_container';
	
	public function index() {
		$this->paginate = [
			'public' => !$this->Auth->user('is_admin'),
		];
		$podcasts = $this->paginate();
		
		$recentEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.published' => 'DESC'],
			'limit' => 12,
		]);

		$this->set(compact('podcasts', 'recentEpisodes'));
	}

	public function edit($id = null) {
		$this->Crud->setSuccessRedirect([
			'action' => 'view',
			'slug' => '__ID__',
		]);
		$this->Crud->update($id);
	}

	public function view($id = null) {
		$id = $this->fetchId($id);
		$result = $this->Crud->read($id, [
			'query' => [
				'public' => !$this->Auth->user('is_admin'),
				'cache' => true,
			]
		]);

		$this->paginate = [
			'PodcastEpisode' => [
				'contain' => ['Podcast'],
				'recursive' => -1,
				'public' => !$this->Auth->user('is_admin'),
				'conditions' => [
					'PodcastEpisode.podcast_id' => $id,
				],
				'order' => [
					'PodcastEpisode.episode_number' => 'DESC',
				],
				'cache' => true,
				'limit' => 100,
			]
		];
		$podcastEpisodes = $this->paginate('PodcastEpisode');

		$articles = $this->Podcast->Article->find('all', [
			'podcastId' => $id,
			'public' => 1,
		]);

		$recentEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.published' => 'DESC'],
			'limit' => 10,
			'cache' => true,
		]);

		$recentPodcastEpisode = $this->Podcast->PodcastEpisode->find('first', [
			'public' => 1,
			'conditions' => ['PodcastEpisode.podcast_id' => $id],
			'order' => ['PodcastEpisode.published' => 'DESC'],
			'cache' => true,
		]);

		$isEditor = $this->Auth->user('is_admin');
		$this->set(compact('podcastEpisodes', 'recentEpisodes', 'isEditor', 'articles', 'recentPodcastEpisode'));

		$this->set([
			'title_for_layout' => $result['Podcast']['title'],
			'description_for_layout' => $result['Podcast']['description'],
			'image_for_layout' => $result['Podcast']['uploadable']['banner']['sizes']['banner-share']['src'],
		]);
	}

	public function itunes($id = null) {
		$id = $this->fetchId($id);
		$result = $this->Crud->read($id, [
			'query' => ['recursive' => -1]
		]);
		$result = $result['Podcast'];
		if (!empty($result['itunes_url'])) {
			$this->redirect($result['itunes_url']);
		} else {
			$this->redirect(Router::url(['action' => 'view', 'slug' => $result['slug']], true));
		}
	}


	public function feed($id = null) {
		//header('Content-Type: application/rss+xml; charset=utf-8');
		$this->response->type('rss');
		$id = $this->fetchId($id);

		$this->Crud->read($id, [
			'query' => [
				'recursive' => -1,
				'cache' => true,
			]
		]);
		$podcastEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'cache' => true,
			'public' => true,
			'recursive' => -1,
			'conditions' => [
				'PodcastEpisode.podcast_id' => $id,
			],
		]);
		$this->set(compact('podcastEpisodes'));
		$this->layout = 'rss/default';
		$this->render('rss/view');
	}

	public function admin_index() {
		$podcasts = $this->paginate();
		$this->set(compact('podcasts'));
	}

	public function admin_view($id = null) {
		$this->Crud->read($id);
	}

	public function admin_edit($id = null) {
		$this->Crud->update($id);
	}

	public function admin_add() {
		$default = [
			'Podcast' => [
				'auto_slug' => 1,
			]
		];
		$this->Crud->create(compact('default'));
	}

	public function admin_delete($id = null) {
		if ($this->request->is('post')) {
			$this->Crud->delete($id);
		}
	}

	public function _setFormElements() {
		$users = $this->Podcast->User->find('list');
		$this->set(compact('users'));
	}	

	public function isAuthorized($user) {
		if ($this->request->action == 'edit') {
			$id = $this->request->params['pass'][0];
			return $this->Podcast->isEditor($id, $this->Auth->user('id'));
		}
		return parent::isAuthorized($user);
	}
}