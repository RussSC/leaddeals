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
		$id = $this->fetchId($id);	
		$result = $this->Crud->read($id, [
			'query' => [
				'public' => !$this->Auth->user('is_admin'),
			]
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

		$this->set([
			'title_for_layout' => '"' . $result['Podcast']['title'] . '" Podcast',
			'description_for_layout' => $result['Podcast']['description'],
			'image_for_layout' => $result['Podcast']['uploadable']['banner']['sizes']['banner-share']['src'],
		]);
	}

	public function feed($id = null) {
		$id = $this->fetchId($id);

		$this->Crud->read($id, [
			'query' => [
				'recursive' => -1,
			]
		]);
		$podcastEpisodes = $this->Podcast->PodcastEpisode->find('all', [
			'public' => true,
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

	private function fetchId($id = null) {
		$named = [];
		if (!empty($this->request->named)) {
			$named = $this->request->named;
		}
		
		if (empty($id)) {
			if (!empty($named['id'])) {
				$id = $named['id'];
			} else if (!empty($named['slug'])) {
				$slug = $named['slug'];
			}
		}
		if (!is_numeric($id) && empty($slug)) {
			$slug = $id;
			$id = null;
		}
		if (empty($id) && !empty($slug)) {
			$id = $this->Podcast->findIdFromSlug($slug);
		}
		return $id;
	}
}