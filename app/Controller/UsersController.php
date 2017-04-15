<?php
class UsersController extends AppController {
	public $components = ['FormData.Crud'];
	public $helpers = [
		'Layout.DisplayText',
		'Layout.Table',
		'Uploadable.FieldUploadImage', 
	];
	
	//public $layout = 'default_container';

	public function beforeFilter($options = []) {
		parent::beforeFilter($options);
		$this->Auth->allow(['add']);
	}

	public function beforeRender($options = []) {
		parent::beforeRender($options);
		if (!empty($this->viewVars['user']['User']['id'])) {
			$this->set('isEditor', $this->_isEditor($this->viewVars['user']['User']['id']));
		}
	}

	private function _isEditor($id) {
		return $this->User->isEditor($id, $this->Auth->user('id'));
	}

	public function _afterSaveData($created) {
		if ($created && empty($this->request->params['prefix'])) {
			$user = $this->User->read();
			$this->Auth->login($user['User']);
		}
	}

	private function _authorizeEditor($id) {
		if (!$this->_isEditor($id)) {
			$this->Auth->deny();
			$this->redirect($this->Auth->redirectUrl());
		}
	}

	public function index() {
		$users = $this->paginate();
		$this->set(compact('users'));
	}

	public function add() {
		$this->Crud->create();
	}

	public function edit($id = null) {
		$this->_authorizeEditor($id);
		$this->Crud->update($id);
	}

	public function view($id = null) {
		$result = $this->Crud->read($id);
		$onlyPublic = !$this->Auth->user('is_admin') && ($id != $this->Auth->user('id'));
		$podcasts = $this->User->Podcast->find('all', [
			'recursive' => -1,
			'hasUser' => $id,
			'public' => $onlyPublic,
		]);
		$podcastEpisodes = $this->User->PodcastEpisode->find('all', [
			'recursive' => -1,
			'public' => $onlyPublic,
			'hasUser' => $id,
			'limit' => 8,
		]);

		$this->set(compact('podcasts', 'podcastEpisodes'));
	}

	public function you() {
		$id = $this->Auth->user('id');
		if (empty($id)) {
			$this->redirect(['action' => 'index']);
		} else {
			$this->redirect(['action' => 'view', $id]);
		}
		/*
		$result = $this->Crud->read($id);
		$podcasts = $this->User->Podcast->find('all', [
			'recursive' => -1,
			'hasUser' => $id,
			//'public' => $onlyPublic,
		]);
		$podcastEpisodes = $this->User->PodcastEpisode->find('all', [
			'recursive' => -1,
			//'public' => $onlyPublic,
			'hasUser' => $id,
			'limit' => 8,
		]);		
		$this->set(compact('podcasts', 'podcastEpisodes'));
		*/
	}

	/* LOGIN */
	public function login() {
		if ($this->Auth->user('id')) {
			$this->Flash->info('You are already logged in');
			$this->redirect('/');
		}

		$this->response->disableCache();
		$this->set('title_for_layout','Please login to your account');
		
		$data = !empty($this->request->data['User']) ? $this->request->data['User'] : [];
		$query = !empty($this->request->query) ? $this->request->query : [];
		$named = !empty($this->request->named) ? $this->request->named : [];

		// Looks for email
		$email = null;
		if (!empty($data['email'])) {
			$email = $data['email'];
		} else if (!empty($named['email'])) {
			$email = $named['email'];
		}
		$data['email'] = $email;

		// Looks for redirect
		if (empty($data['redirect'])) {
			$defaultRedirect = null;
			if (!array_key_exists('redirect', $named) || $named['redirect'] != false) {
				$redirect = $this->referer();
			}
			if (empty($redirect)) {
				$redirect = $defaultRedirect;
			}
			$data['redirect'] = Router::url($redirect, true);
		} else {
			$redirect = $data['redirect'];
		}

		$loginOptions = [];
		if (!empty($redirect)) {
			$loginOptions['redirect'] = $redirect;
		}

		// Saves Data
		$allowLogin = true;

		// Creates new profile
		if (isset($data['create'])) {
			if (!$this->User->save($data)) {
				$allowLogin = false;
			} else {
				$data['password'] = $data['new_password'];
			}
		}

		$this->request->data['User'] = $data;

		// Logs in user
		if ($allowLogin && $this->request->is('post')) {
			if ($user = $this->Auth->login()) {
				$this->RememberMe->set();
				if (empty($redirect)) {
					$redirect = $this->Auth->redirect();
				}
				return $this->redirect($redirect);
			} else {
				$this->Flash->warning('Username or password is incorrect');
			}
		}
		unset($this->request->data['User']['password']);
	}
	
	public function logout() {
		$this->response->disableCache();
		$this->RememberMe->delete();
		$this->Auth->logout(['redirect' => false]);
	}	

	public function admin_index() {
		$users = $this->paginate();
		$this->set(compact('users'));
	}

	public function admin_add() {
		$this->Crud->create();
	}

	public function admin_edit($id = null) {
		$this->Crud->update($id);
	}

	public function admin_view($id = null) {
		// Redirects for now until we need an admin view for users
		$this->redirect(['admin' => false, 'action' => 'view', $id]);
		$this->Crud->read($id);
	}
}