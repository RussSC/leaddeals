<?php 
class PodcastsController extends AppController {
	public $name = 'Podcasts';
	public $components = ['FormData.Crud'];
	public $helpers = [
		'Rss' => [
			'className' => 'AppRss',
		], 
		'Uploadable.FieldUploadImage',
		'Time',
	];

	public $layout = 'default_container';
	
	public function index() {
		$this->paginate = [
			'public' => !$this->Auth->user('is_admin'),
		];
		$podcasts = $this->paginate();
		
		$recentEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.posted' => 'DESC'],
			'limit' => 10,
		]);

		$this->set(compact('podcasts', 'recentEpisodes'));
	}

	public function view($id = null) {
		$result = $this->Crud->read($id, [
			'public' => !$this->Auth->user('is_admin'),
		]);
		$this->paginate = [
			'PodcastEpisode' => [
				'public' => !$this->Auth->user('is_admin'),
				'conditions' => [
					'PodcastEpisode.podcast_id' => $id,
				],
				'order' => [
					'PodcastEpisode.episode_number' => 'DESC',
				]
			]
		];
		$podcastEpisodes = $this->paginate('PodcastEpisode');
		
		$recentEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.posted' => 'DESC'],
			'limit' => 10,
		]);

		$isEditor = $this->Auth->user('is_admin');
		$this->set(compact('podcastEpisodes', 'recentEpisodes', 'isEditor'));
	}

	public function feed($id = null) {
		$this->Crud->read($id, [
			'recursive' => -1,
			'public' => true,
		]);
		$podcastEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => true,
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
}