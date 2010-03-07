<?php
class Service extends AppModel {
	var $name = 'Service';
	var $validate = array();
	var $order = 'Service.modified';
	var $recursive = 2;

	var $hasMany = array('Note'=>array());
	var $belongsTo = array('Website','Customer','User');

	function beforeSave() {
		if(empty($this->id) && empty($this->data[$this->name]['joined']))
			$this->data[$this->name]['joined'] = strftime('%Y-%m-%d %T');
		return true;
	}
	
	function cancel() {
		if(!empty($this->data[$this->name]['id']) && !empty($this->data[$this->name]['cancelled'])) {
			$this->data[$this->name]['status'] = 2;
			$this->save($this->data);
		} else {
			return false;
		}
	}

}
?>