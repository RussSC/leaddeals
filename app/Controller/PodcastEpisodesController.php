<?php 
class PodcastEpisodesController extends AppController {
	public $name = 'PodcastEpisodes';
	public $components = ['FormData.Crud'];
	public $helpers = [
		'Uploadable.FieldUploadImage',
		'Layout.Table',
	];

	public $layout = 'default_container';
	
	public function index() {
		$this->redirect(['controller' => 'podcasts']);
	}
	
	public function view($id = null) {
		$this->Crud->read($id, [
			'public' => !$this->Auth->user('is_admin'),
		]);
		$this->set('neighbors', $this->PodcastEpisode->findNeighbors($id, [
			'public' => !$this->Auth->user('is_admin'),
		]));

		$recentEpisodes = $this->PodcastEpisode->find('all', [
			'public' => !$this->Auth->user('is_admin'),
			'order' => ['PodcastEpisode.posted' => 'DESC'],
			'limit' => 10,
		]);

		$isEditor = $this->PodcastEpisode->isEditor($id, $this->Auth->user('id'));
		$this->set(compact('recentEpisodes', 'isEditor'));
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
				'posted' => date('Y-m-d H:i:s'),
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
				'posted' => date('Y-m-d H:i:s'),
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