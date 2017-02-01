<?php
class CacheQueryBehavior extends ModelBehavior {
	public $name = 'CacheQuery';
	public $settings;
	
	public function setup(Model $Model, $settings = []) {
		$default = [
			'configKey' => $Model->alias . '-config',
			'config' => $this->getCacheConfig($Model)
		];

		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $default;
		}
		if (!empty($settings)) {
			$this->settings[$Model->alias] = array_merge(
				$this->settings[$Model->alias], (array) $settings
			);
		}
	}
	
	#region Callbacks
	public function afterSave(Model $Model, $created, $options = []) {
		$Model->deleteCache();
		return parent::afterSave($Model, $created, $options);
	}
	
	public function afterDelete(Model $Model) {
		$Model->deleteCache();
		return parent::afterDelete($Model);
	}
	#endregion

	public function findCacheCheck($Model, $type, $query) {
		if (!($cacheKey = $Model->hasFindCache($query))) {
			return false;
		} else {
			return $Model->findCache($type, $query, $cacheKey);
		}
	}
	
//Checks to see if a $query contains the correct cache flags
//Returns the cache key
	public function hasFindCache($Model, $query) {
		$return = false;
		if ($hasFindCache = Configure::read('Cache.disable') !== true && 
			Configure::read('Cache.check') === true && 
			isset($query['cache']) && 
			$query['cache'] !== false) {
			$return = true;
			if ($query['cache'] !== true) {
				$return = $query['cache'];
			}
		}
		return $return;
	}

/**
 * Finds the results from a cache
 *
 * @param Model $Model parent model
 * @param string $type Type of query
 * @param Array $query The CakePHP query options
 * @param bool|string $key The cache key
 *		- bool (default) Set to true for default value
 *		- string The name of the key
 * @return Results array if success, false if not available
 */
	public function findCache($Model, $type = 'first', $query, $key = true, $duration = null) {
		$configKey = $this->settings[$Model->alias]['configKey'];

		list($key, $duration) = $this->cacheKeySplit($Model, null, $key, $duration);
		$key = $type . '-' . $key;
		
		// Set cache settings
		if (!is_numeric($duration)) {
			$duration = strtotime($duration) - time();
		}
		$config = $this->setCacheConfig($Model, compact('duration'));

		// Load from cache
		//Cache::set('duration', $duration, $configKey);
		$results = Cache::read($key, $configKey);
		$found = is_array($results);

		if (!$found) {
			// debug("$key is not found");
			// No cache found
			unset($query['cache']);
			$results = $Model->find($type, $query);

			//Cache::set('duration', $duration, $configKey);
			Cache::write($key, $results, $configKey);
		} else {
			// debug("$key is found");
		}

		return $results;
	}

	public function cacheKeySplit(Model $Model, $query = [], $defaultKey = null, $defaultDuration = null) {
		$key = $defaultKey;
		$duration = $duration = $this->settings[$Model->alias]['config']['duration'];

		if (!empty($defaultDuration)) {
			$duration = $defaultDuration;
		}
		if (!empty($query['cache'])) {
			$key = $query['cache'];
		}
		if (is_array($key)) {
			list($key, $duration) = $key + [null, $duration];
		} 	

		return [$key, $duration];
	}

/**
 * Removes cached model data
 *
 **/
	public function deleteCache($Model, $name = null, $configKey = null) {
		if (empty($configKey)) {
			$configKey = $this->settings[$Model->alias]['configKey'];
		}

		$this->setCacheConfig($Model);

		if ($name) {
			Cache::delete($name, $configKey);
		} else {
			Cache::clear(false, $configKey);
		}
	}
	
	public function setCacheConfig($Model, $config = []) {
		$configKey = $this->settings[$Model->alias]['configKey'];
		$config += $this->settings[$Model->alias]['config'];
		return Cache::config($configKey, $config);
	}

	private function getCacheConfig($Model, $config = []) {
		return array_merge([
			'duration' => '+1 hour',
			'engine' => 'File',
			'prefix' => strtolower($Model->name),
		], $config);
	}
}