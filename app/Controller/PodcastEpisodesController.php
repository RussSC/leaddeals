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
		$isEditor = $this->PodcastEpisode->isEditor($id, $this->Auth->user('id'));
		
		$result = $this->Crud->read($id, [
			'query' => [
				'public' => !$isEditor,
				'contain' => ['Podcast' => ['PodcastLink'], 'User'],
				'cache' => true,
			]
		]);

		if (empty($result['PodcastEpisode']['active']) && empty($result['PodcastEpisode']['download_url'])) {
			$this->redirect(['action' => 'edit', $id]);
		}
		
		if (empty($slug) && !empty($result['PodcastEpisode']['slug'])) {
			$this->redirect([
				'action' => 'view',
				$result['PodcastEpisode']['id'],
				$result['PodcastEpisode']['slug']
			]);
		}

		$this->set('neighbors', $this->PodcastEpisode->findNeighbors($id, [
			'public' => !$isEditor,
			'cache' => true,
		]));

		$podcastEpisodes = $this->PodcastEpisode->find('all', [
			'public' => !$isEditor,
			'contain' => ['Podcast'],
			'conditions' => [
				'PodcastEpisode.podcast_id' => $result['Podcast']['id'],
			],
			'cache' => true,
			'order' => ['PodcastEpisode.published' => 'DESC'],
		]);

		$recentEpisodes = $this->PodcastEpisode->find('all', [
			'public' => !$isEditor,
			'order' => ['PodcastEpisode.published' => 'DESC'],
			'limit' => 10,
		]);

		$articles = $this->PodcastEpisode->Article->find('all', [
			'podcastEpisodeId' => $id,
			'public' => 1,
		]);

		$this->set(compact('podcastEpisodes', 'recentEpisodes', 'isEditor'));

		$this->set([
			'title_for_layout' => $result['PodcastEpisode']['full_title'],
			'description_for_layout' => $result['PodcastEpisode']['description']
		]);

		if (!empty($result['PodcastEpisode']['uploadable']['banner']['sizes']['banner-share']['src'])) {
			$this->set('image_for_layout', $result['PodcastEpisode']['uploadable']['banner']['sizes']['banner-share']['src']);
		}

	}

	public function player($id = null) {
		$this->Crud->read($id, [
			'public' => !$this->PodcastEpisode->isEditor($id, $this->Auth->user('id')),
		]);
		$this->layout = 'popup';
	}

	public function add($podcastId = null) {
		if (empty($podcastId)) {
			$this->Flash->error('Please select a podcast first', ['redirect' => true]);
		} else if (!$this->PodcastEpisode->Podcast->isEditor($podcastId, $this->Auth->user('id'))) {
			$this->Flash->error('You do not have permission to add a podcast episode', ['redirect' => true]);
		}

		$podcast = $this->PodcastEpisode->Podcast->find('first', [
			'contain' => ['User'],
			'conditions' => [
				'Podcast.id' => $podcastId,
			]
		]);

		$userIds = Hash::extract($podcast, 'User.{n}.id');
		$podcast = $podcast['Podcast'];

		$default = [
			'PodcastEpisode' => [
				'podcast_id' => $podcastId,
				'episode_number' => $this->PodcastEpisode->Podcast->getNewEpisodeNumber($podcastId),
				'published' => date('Y-m-d H:i:s'),
				'explicit' => $podcast['explicit'],
				'keywords' => $podcast['keywords'],
			],
		];

		foreach ($userIds as $userId) {
			$default['PodcastEpisodesUser'][] = ['user_id' => $userId];
		}

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
			'public' => !$this->PodcastEpisode->isEditor($id, $this->Auth->user('id')),
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

	public function isAuthorized($user) {
		$action = $this->request->action;
		$userId = $this->Auth->user('id');
		if ($action == 'edit' || $action == 'delete') {
			$id = $this->request->params['pass'][0];
			return $this->PodcastEpisode->isEditor($id, $userId);
		} else if ($action == 'add') {
			$podcastId = $this->request->params['pass'][0];
			return $this->PodcastEpisode->Podcast->isEditor($podcastId, $userId);
		}
		return parent::isAuthorized($user);

	}
}
