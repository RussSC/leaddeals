<?php 
class PodcastEpisodesController extends AppController {
	public $name = 'PodcastEpisodes';
	public $components = ['FormData.Crud'];
	public $helpers = [
		'Uploadable.FieldUploadImage',
		'Layout.Table',
	];

	//public $layout = 'default_container';
	
	public function index() {
		$this->redirect(['controller' => 'podcasts']);
	}
	
	public function view($id = null, $slug = null) {
		$id = $this->fetchId($id);
		$result = $this->Crud->read($id, [
			'query' => [
				'public' => !$this->Auth->user('is_admin'),
				'contain' => ['Podcast' => ['PodcastLink']],
				'cache' => true,
			]
		]);
		
		if (empty($slug) && !empty($result['PodcastEpisode']['slug'])) {
			$this->redirect([
				'action' => 'view',
				$result['PodcastEpisode']['id'],
				$result['PodcastEpisode']['slug']
			]);
		}

		$this->set('neighbors', $this->PodcastEpisode->findNeighbors($id, [
			'public' => !$this->Auth->user('is_admin'),
			'cache' => true,
		]));

		$podcastEpisodes = $this->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'contain' => ['Podcast'],
			'conditions' => [
				'PodcastEpisode.podcast_id' => $result['Podcast']['id'],
			],
			'cache' => true,
			'order' => ['PodcastEpisode.published' => 'DESC'],
		]);

		$recentEpisodes = $this->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.published' => 'DESC'],
			'limit' => 10,
		]);

		$articles = $this->PodcastEpisode->Article->find('all', [
			'podcastEpisodeId' => $id,
			'public' => 1,
		]);

		$isEditor = $this->PodcastEpisode->isEditor($id, $this->Auth->user('id'));
		$this->set(compact('podcastEpisodes', 'recentEpisodes', 'isEditor'));

		$this->set([
			'title_for_layout' => $result['PodcastEpisode']['full_title'],
			'description_for_layout' => $result['PodcastEpisode']['description'],
			'image_for_layout' => $result['PodcastEpisode']['uploadable']['banner']['sizes']['banner-share']['src'],
		]);

	}

	public function player($id = null) {
		$this->Crud->read($id, [
			'public' => !$this->Auth->user('is_admin'),
		]);
		$this->layout = 'popup';
	}

	public function add($podcastId = null) {
		if (empty($podcastId)) {
			$this->Flash->error('Please select a podcast first', ['redirect' => true]);
		} else if (!$this->PodcastEpisode->Podcast->isEditor($podcastId, $this->Auth->user('id'))) {
			$this->Flash->error('You do not have permission to add a podcast episode', ['redirect' => true]);
		}

		$podcast = $this->PodcastEpisode->Podcast->read(null, $podcastId);
		$podcast = $podcast['Podcast'];

		$default = [
			'PodcastEpisode' => [
				'podcast_id' => $podcastId,
				'episode_number' => $this->PodcastEpisode->Podcast->getNewEpisodeNumber($podcastId),
				'published' => date('Y-m-d H:i:s'),
				'explicit' => $podcast['explicit'],
				'keywords' => $podcast['keywords'],
			]
		];
		$this->Crud->create(compact('default'));
	}

	public function edit($id = null) {
		$result = $this->Crud->update($id);
		if (!$this->PodcastEpisode->isEditor($id, $this->Auth->user('id'))) {
			$this->Flash->error('You do not have permission to edit this episode', ['redirect' => true]);
		}
	}

	public function download($id = null) {
		$result = $this->Crud->read($id, [
			'public' => !$this->Auth->user('is_admin'),
		]);
		$this->PodcastEpisode->setDownloaded($id, $_SERVER['REMOTE_ADDR']);
		$this->redirect($result['PodcastEpisode']['download_url']);
	}

	public function admin_index() {
		$podcastEpisodes = $this->paginate();
		$this->set(compact('podcastEpisodes'));
	}

	public function admin_edit($id = null) {
		$this->Crud->update($id);
	}

	public function admin_add($podcastId = null) {
		$default = [
			'PodcastEpisode' => [
				'podcast_id' => $podcastId,
				'episode_number' => $this->PodcastEpisode->Podcast->getNewEpisodeNumber($podcastId),
				'published' => date('Y-m-d H:i:s'),
			]
		];
		$this->Crud->create(compact('default'));
	}

	public function admin_view($id = null) {
		$this->layout = 'admin/view';
		$this->Crud->read($id);
	}

	public function admin_delete($id = null) {
		if ($this->request->is('post')) {
			$this->Crud->delete($id);
		}
	}

	public function _setFormElements() {
		$users = $this->PodcastEpisode->User->find('list');
		$this->set(compact('users'));
	}
}
