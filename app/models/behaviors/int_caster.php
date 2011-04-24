<?php
class IntCasterBehavior extends ModelBehavior {
	var $fields = array();
	var $_defaults = array(
		'cacheConfig' => 'default'
	);

	function setup(&$model, $config = array()) {
		if (is_array($config)) {
			$settings = array_merge($this->_defaults,$config);
			$this->settings[$model->alias] = $settings;
		}
		$this->Model =& $model;
		$this->getCachedFields();
	}

	function afterFind(&$model, $results, $primary) {
		$alias = $model->alias;
		if ($primary && !empty($results[0])) {
			foreach ($results as $x =>$result) {
				foreach ($this->fields[$alias] as $field) {
					if (array_key_exists($field,$result[$alias]) && is_string($result[$alias][$field])) {
						$results[$x][$alias][$field] = (integer) $result[$alias][$field];
					}
				}
			}
		}
		return $results;
	}

	private function getCachedFields() {
		$alias = $this->Model->alias;
		if ($fields = Cache::read('caster_fields',$this->settings[$alias]['cacheConfig'])) {
			$this->fields = $fields;
			if (!array_key_exists($alias,$this->fields)) {
				$this->fields[$alias] = $this->getCurrentFields();
				$this->setCachedFields();
			}
		} else {
			$this->fields[$alias] = $this->getCurrentFields();
			$this->setCachedFields();
		}
	}

	private function setCachedFields() {
		$alias = $this->Model->alias;
		return Cache::write('caster_fields',$this->fields,$this->settings[$alias]['cacheConfig']);
	}

	private function getCurrentFields() {
		$schema = $this->Model->schema();
		$fields = array();
		foreach ($schema as $fieldName => $f) {
			if ($f['type'] == 'integer') {
				$fields[] = $fieldName;
			}
		}
		return $fields;
	}
}
