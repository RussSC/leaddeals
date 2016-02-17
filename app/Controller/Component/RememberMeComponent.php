<?php
/**
 * Component to handle storing login information in a cookie for use later
 *
 * @see http://stackoverflow.com/questions/12447487/cakephp-remember-me-with-auth
 * @package app.Controller.Component
 **/
App::uses('Component', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class RememberMeComponent extends Component {
	public $name = 'RememberMe';
	public $components = array('Cookie', 'Auth', 'Session');

	public $controller;
	public $settings;

	private $_modelName;
	private $_className;
	private $_username;
	private $_password;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge(array(
			'field' => 'remember_me',
			'cookieName' => 'remember_me_cookie',
			'encrypt' => true,
			'expires' => '2 weeks',
		), $settings);
		return parent::__construct($collection, $settings);
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;

		// Fetches FormAuthenticate information
		$authenticateObjects = $this->Auth->constructAuthenticate();
		foreach ($authenticateObjects as $object) {
			if (get_class($object) == 'FormAuthenticate') {
				$this->_modelName = $object->settings['userModel'];
				$this->_username = $object->settings['fields']['username'];
				$this->_password = $object->settings['fields']['password'];
				list(,$this->_className) = pluginSplit($this->_modelName);
			}
		}

		// Set Cookie Options
		$this->Cookie->key = '2304983204980(*)(UIlkjlkdf0s980sdjlkjerowiur)(*&)(*kjlkjrwelkjre';
		$this->Cookie->httpOnly = true;

		if (!$this->Auth->loggedIn() && $this->Cookie->read($this->settings['cookieName'])) {
			$cookie = $this->Cookie->read($this->settings['cookieName']);

			$User = ClassRegistry::init($this->_modelName);
			$user = $User->find('first', array(
				'conditions' => array(
					$User->escapeField($this->_username) => $cookie[$this->_username],
					$User->escapeField($this->_password) => $cookie[$this->_password],
				)
			));

			if ($user && !$this->Auth->login($user[$this->_className])) {
				$this->controller->redirect($this->Auth->logoutRedirect);
			}
		}
		return parent::initialize($controller);
	}

// Sets cookie variable
	public function set() {
		if ($this->controller->request->is('post') && isset($this->controller->request->data[$this->_className])) {
			$data = $this->controller->request->data[$this->_className];

			if (!empty($data[$this->settings['field']])) {
				// Remove "remember me" choice
				unset($data[$this->settings['field']]);

				// Hash password
				$passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
				$hashPassword = $passwordHasher->hash($data['password']);
				$data[$this->_password] = $hashPassword;

				// Write cookie
				return $this->Cookie->write(
					$this->settings['cookieName'],
					$data,
					$this->settings['encrypt'],
					$this->settings['expires']
				);
			}
		}
		return null;
	}

// Deletes the cookie variable
	public function delete() {
		if ($this->Cookie->check($this->settings['cookieName'])) {
			$this->Cookie->delete($this->settings['cookieName']);
		}
	}
}