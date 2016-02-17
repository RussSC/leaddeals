<?php
/**
 * PublicConditionBehavior
 * Author: Jamie Clark
 * Date: 11/21/2013
 *
 * A CakePHP Model Behavior that allows you to define complex conditions for what constitutes "Public"
 * After the conditions have been setup, you only need to pass array('public' => true) in your query data
 *
 **/
class PublicConditionsBehavior extends ModelBehavior {
	public $name = 'PublicConditions';

	//Conditions to apply to a query that has been marked public
	public $conditions;
	
	public function setup(Model $Model, $conditions = array()) {
		$default = array();
		if (!isset($this->conditions[$Model->alias])) {
			$this->conditions[$Model->alias] = $default;
		}
		
		if (!empty($conditions)) {
			$this->conditions[$Model->alias] = array_merge($this->conditions[$Model->alias], $conditions);
		}
	}
	
	public function beforeFind(Model $Model, $queryData = array()) {
		if (!empty($queryData['public'])) {
			unset($queryData['public']);
			$queryData = $this->getPublicQueryData($Model, $queryData);
		}
		return $queryData;
	}
	
	
	private function setQueryDataConditions($Model, $queryData) {
		if (!isset($queryData['conditions'])) {
			$queryData['conditions'] = array();
		}
		$conditions = array();
		//Includes any conditions set in the settings of the setup function
		if (!empty($this->conditions[$Model->alias])) {
			$conditions = $this->conditions[$Model->alias];
		}
		//Checks the model for a custom function
		$conditions = $this->getPublicConditions($Model, $conditions);
		$queryData['conditions'] = array_merge($queryData['conditions'], (array) $conditions);
		return $queryData;
	}

	//Checks the model for a function to calculate public query data
	private function getPublicQueryData($Model, $queryData = array()) {
		if ($Model->hasMethod('publicQueryData')) {
			$queryData = $Model->publicQueryData($queryData);
		}
		$queryData = $this->setQueryDataConditions($Model, $queryData);
		return $queryData;
	}
	
	//Checks the model for a function to calculate only the public conditions
	private function getPublicConditions($Model, $conditions = array()) {
		if ($Model->hasMethod('publicConditions')) {
			$conditions = $Model->publicConditions($conditions);
		}
		return $conditions;
	}
}