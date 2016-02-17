<?php
App::uses('Component', 'Controller');

class LayoutDataComponent extends Component {
	public $name = 'LayoutData';

	protected $controller;

	private $_layoutData = [];

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	public function startup(Controller $controller) {
		try {
			$this->setModelVars();
		} catch (Exception $e) {

		}
		return parent::startup($controller);
	}

	public function beforeRender(Controller $controller) {
		try {
			$this->setResultVars();
			$this->controller->set('layoutData', $this->_layoutData);
		} catch (Exception $e) {
			
		}
		return parent::beforeRender($controller);
	}

	protected function hasModel() {

	}

	public function setModelVars($data = []) {
		$modelName = !empty($data['modelName']) ? $data['modelName'] : $this->controller->modelClass;
		if (empty($modelName)) {
			return false;
		}
		$modelHuman = Inflector::humanize(Inflector::underscore($modelName));
		$modelHumanPlural = Inflector::pluralize($modelHuman);
		$controller = Inflector::tableize($modelName);

		if (empty($this->controller->{$modelName})) {
			return false;
		}

		$primaryKey = $this->controller->{$modelName}->primaryKey;
		$displayField = $this->controller->{$modelName}->displayField;
		$default = compact(
			'modelName', 'modelHuman', 'modelHumanPlural',
			'controller', 'primaryKey', 'displayField'
		);
		$this->_appendLayoutData($data, $default);
		return true;
	}

	public function setResultVars($data = []) {
		if (empty($this->_layoutData['modelName'])) {
			if (!$this->setModelVars($data)) {
				return false;
			}
		}

		$modelName = $this->_layoutData['modelName'];

		$variableSingle = Inflector::variable($modelName);
		$variablePlural = Inflector::pluralize($variableSingle);
		if (!empty($this->controller->viewVars[$variableSingle])) {
			$result = $this->controller->viewVars[$variableSingle];
		}
		if (!empty($this->controller->viewVars[$variablePlural])) {
			$results = $this->controller->viewVars[$variablePlural];
		}
		$default = compact(
			'variableSingle', 'variablePlural', 
			'result', 'results'
		);
		$this->_appendLayoutData($data, $default);
		return true;
	}

	public function checkLayout($layoutPath) {
		$filepath = APP . 'View' . DS . 'Layouts' . DS . $layoutPath . '.ctp';
		return is_file($filepath);
	}

	private function _appendLayoutData($data, $default) {
		if (!empty($data)) {
			$data = array_merge($default, array_intersect_key($data, $default));
		} else {
			$data = $default;
		}
		$this->_layoutData = $data + $this->_layoutData;
	}
}