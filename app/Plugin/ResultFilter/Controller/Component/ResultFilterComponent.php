<?php
class ResultFilterComponent extends Component {
	public $components = ['Session'];

	public $controller;
	public $request;

	const SESSION_KEY 	= 'RESULT_FILTER';
	const DATA_KEY 		= 'ResultFilter';
	const QUERY_KEY 	= 'ResultFilter-';

	const RESET_KEY 	= 'ResultFilterReset';

	protected $_defaults;
	protected $_config;

	protected $_data = [];
	protected $_sessionKey;

	//protected $_filterValues = [];
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->request = $controller->request;
		$this->setSessionKey();

		if (!empty($this->request->named[self::QUERY_KEY . 'remove'])) {
			$this->remove($this->request->named[self::QUERY_KEY . 'remove']);
		}

		$this->_initData();
	}

	public function beforeRender(Controller $controller) {
		parent::beforeRender($controller);
		$this->setVars();
	}

	public function set($config) {
		$this->_defaults = [];
		foreach ($config as $key => $vals) {
			$config[$key] = array_merge([
				'key' => $key,
				'label' => $key,
				'default' => null,
			], $vals);
			if (!empty($config[$key]['default'])) {
				$this->_defaults[$key] = $config[$key]['default'];
			}
		}
		$this->_config = $config;
		$this->setData($this->_dataWithDefaults($this->_data));
	}

	public function remove($key) {
		$this->Session->delete($this->_sessionKey . '.' . $key);
		unset($this->_config[$key]['value']);
		unset($this->_config[$key]['displayValue']);
		unset($this->_data[$key]);

		$data = $this->getData(false);
		unset($data[$key]);
		$this->setData($data);

		$this->redirect();
	}

	public function filter($query = []) {
		foreach ($this->_data as $key => $val) {
			if (method_exists($this->controller, '_setResultFilterValue')) {
				$oQuery = $query;
				$query = $this->controller->_setResultFilterValue($key, $val, $query);
				if ($query != $oQuery) {
					$this->setFilterValue($key, $val);
				}
			}
		}
		return $query;
	}

	public function redirect($url = null, $data = null) {
		if (empty($data)) {
			$data = $this->getData(false);
		}
		if (empty($url)) {
			$url = $this->getUrl();
		}
		foreach ($data as $k => $v) {
			$url['?'][self::QUERY_KEY . $k] = $v === null ? false : $v;
		}
		return $this->controller->redirect($url);
	}

	public function getUrl() {
		$url = [];
		$copyKeys = ['controller', 'action', 'plugin'];
		$params = $this->request->params;
		$query = $this->request->query;

		foreach ($copyKeys as $key) {
			if (!empty($params[$key])) {
				$url[$key] = $params[$key];
			}
		}
		if (!empty($params['prefix'])) {
			$url[$params['prefix']] = true;
		}
		
		if (!empty($query)) {
			foreach ($query as $k => $v) {
				if (!($isFilterKey = $this->getFilterKey($k))) {
					$url[$k] = $v;
				}
			}
		}
		return $url;
	}

	protected function _initData() {
		$data = $this->getData();
		$reset = !empty($data[self::RESET_KEY]);

		// Resets all data
		if ($reset) {
			$data = $this->_defaults;
		}
		
		$this->setData($data);

		if ($reset) {
			$this->redirect(null, $data);
		}
	}

	protected function getData($redirect = true) {
		$data = [];
		if (
			($data = $this->_getFromRequestData())
		) {
			if ($redirect) {
				return $this->redirect(null, $this->_dataWithDefaults($data));
			}
		} else if (
			!($data = $this->_getFromNamed()) &&
			!($data = $this->_getFromQuery())
		) {
			// If no named data is found and Session exists, load the session
			if ($data = $this->_getFromSession()) {
				if ($redirect) {
					return $this->redirect(null, $this->_dataWithDefaults($data));
				}
			}
		}
		return $this->_dataWithDefaults($data);
	}

	protected function setData($data) {
		$this->_data = $data;
		foreach ((array) $data as $key => $val) {
			$this->setFilterValue($key, $val);
		}
		$this->controller->request->data[self::DATA_KEY] = $data;
		$this->setVars();
	}

	protected function setVars() {
		$this->Session->write($this->_sessionKey, $this->_data);
		$this->controller->set([
			'resultFilter' => $this->_config,
			'resultFilterUrl' => $this->getUrl(),
		]);
	}

	protected function _getFromRequestData() {
		if (!empty($this->request->data[self::DATA_KEY])) {
			if (!empty($this->_defaults)) {
				$this->request->data[self::DATA_KEY] = array_merge($this->_defaults, $this->request->data[self::DATA_KEY]);
			}
			return $this->request->data[self::DATA_KEY];
		}
		return null;
	}

	protected function _getFromSession() {
		if ($this->Session->check($this->_sessionKey)) {
			return $this->Session->read($this->_sessionKey);
		}
		return null;
	}

	protected function _getFromNamed() {
		$data = [];
		if (!empty($this->request->named)) {
			foreach ($this->request->named as $key => $val) {
				if ($key = $this->getFilterKey($key)) {
					$data[$key] = $val;
				}
			}
		}
		return !empty($data) ? $data : null;
	}

	protected function _getFromQuery() {
		$data = [];
		if (!empty($this->request->query)) {
			foreach ($this->request->query as $key => $val) {
				if ($key = $this->getFilterKey($key)) {
					$data[$key] = $val;
				}
			}
		}
		return !empty($data) ? $data : null;
	}

	protected function _dataWithDefaults($data = []) {
		if (!empty($this->_defaults)) {
			if (!empty($data)) {
				$data = array_merge($this->_defaults, $data);
			} else {
				$data = $this->_defaults;
			}
		}
		return $data;
	}

	protected function getFilterKey($key) {
		if (strpos($key, self::QUERY_KEY) === 0) {
			$key = substr($key, strlen(self::QUERY_KEY));
			if ($key != 'remove') {
				return $key;
			}
		}
		return null;
	}

	protected function setFilterValue($key, $val) {
		$displayVal = $val;
		if (method_exists($this->controller, '_setResultFilterDisplay')) {
			$displayVal = $this->controller->_setResultFilterDisplay($key, $val);
		}
		$this->_data[$key] = $val;
		$this->_config[$key]['value'] = $val;
		$this->_config[$key]['displayValue'] = $displayVal;
	}

	protected function setSessionKey() {
		$keys = ['plugin', 'prefix', 'controller', 'action'];
		$sessionKey = self::SESSION_KEY;
		foreach ($keys as $key) {
			$val = "";
			if (!empty($this->request->params[$key])) {
				$val = $this->request->params[$key];
			}
			$sessionKey .= '_' . $val;
		}
		return $this->_sessionKey = $sessionKey;
	}
}