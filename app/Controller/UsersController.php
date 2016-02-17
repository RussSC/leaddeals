<?php
class UsersController extends AppController {
	public $components = ['FormData.Crud'];
	public $helpers = ['Uploadable.FieldUploadImage', 'Layout.Table'];
	
	public $layout = 'default_container';

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
		if ($created) {
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
		$this->Crud->read($id, ['contain' => ['Podcast']]);
	}

	public function you() {
		$id = $this->Auth->user('id');
		if (empty($id)) {
			$this->redirect(['action' => 'index']);
		}
		$this->Crud->read($id);
	}

	/* LOGIN */
	public function login() {
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
		$redirect = ['controller' => 'users', 'action' => 'view'];

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

		//debug(compact('loginOptions', 'redirect', 'data'));
		//exit();
		// Logs in user
		if ($allowLogin && $this->request->is('post')) {
			if ($user = $this->Auth->login()) {

				if (!empty($redirect)) {
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
		$this->Crud->read($id);
	}
}