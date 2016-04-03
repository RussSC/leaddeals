<?php
class FlashComponentDELETE extends Component {
	public $name = 'Flash';
	public $components = array('Session');

	public $controller;
	public $settings;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = Hash::merge(array(
			'element' => 'default',
			'key' => 'flash',
			'defaultState' => 'info',

			'states' => array(
				'success' => array(
					'class' => 'alert alert-success',
				),
				'info' => array(
					'class' => 'alert alert-info',
				),
				'error' => array(
					'class' => 'alert alert-danger',
				),
				'warning' => array(
					'class' => 'alert alert-warning',
				)
			)
		), $settings);
		parent::__construct($collection, $settings);
	}

	public function __call($method, $args) {
		if ($this->_isState($method)) {
			$state = $method;
			$message = $args[0];
			$params = array();
			if (!empty($args[1])) {
				if (!is_array($args[1])) {
					$args[1] = array($args[1]);
				}
				$params = $args[1];
			}
			$params['state'] = $method;
			return $this->set($message, $params);
		} else {
			throw new Exception("Flash Method '$method' not found");
		}
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		return parent::initialize($controller);
	}
		
	public function set($message, $params = array()) {
		$params = $this->_getStateParams($params);
		$params = array_merge(array(
			'element' => $this->settings['element'],
			'key' => $this->settings['key'],
			'state' => $this->settings['defaultState'],
		), (array) $params);

		$element = $this->_stripKey($params, 'element');
		$state = $this->_stripKey($params, 'state');
		$key = $this->_stripKey($params, 'key');
		$redirect = $this->_stripKey($params, 'redirect');

		if (!empty($message)) {
			$this->Session->setFlash($message, $element, $params, $key);
		}
		
		if ($redirect) {
			$this->redirect($redirect);
		}
	}

	public function redirect($url = true) {
		if ($url === true) {
			$url = $this->controller->referer();
		}
		return $this->controller->redirect($url);
	}

	private function _getStateParams($params = array(), $state = null) {
		if (empty($state)) {
			$state = $this->_getState($params);
		}
		if (!empty($this->settings['states'][$state])) {
			$params = Hash::merge($params, $this->settings['states'][$state]);
		}
		return $params;
	}

	private function _getState($params = array()) {
		if (!empty($params['state'])) {
			return $params['state'];
		} else if (!empty($this->settings['defaultState'])) {
			return $this->settings['defaultState'];
		} else {
			return null;
		}
	}

	private function _isState($state) {
		return !empty($this->settings['states'][$state]);
	}

	private function _stripKey(&$params, $key, $default = null) {
		$val = $default;
		if (!empty($params[$key])) {
			$val = $params[$key];
			unset($params[$key]);
		}
		return $val;
	}
}