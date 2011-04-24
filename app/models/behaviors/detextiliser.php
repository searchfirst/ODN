<?php
class DetextiliserBehavior extends ModelBehavior {
	var  $_defaults = array('fields' => array('description','content'));

	function setup (&$Model, $config = array()) {
		$this->Model =& $model;
		if (is_array($config)) {
			$settings = array_merge($this->_defaults,$config);
			$this->settings[$Model->alias] = $settings;
		}
	}

	function beforeSave(&$Model) {
		$this->deTextilise($Model);
		return true;
	}

	private function deTextilise(&$Model) {
		App::import('Vendor','textile');
		$textile = new Textile();
		$alias = $Model->alias;
		$fields = $this->settings['fields'];
		if (is_array($Model->data) && array_key_exists($alias,$Model->data)) {
			foreach ($fields as $field) {
				if (array_key_exists($field,$Model->data[$alias])) {
					$Model->data[$alias][$field] = $textile->deTextile($Model->data[$alias][$field]);
				}
			}
		}
	}
}
