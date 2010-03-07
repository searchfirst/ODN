<?php
class Schedule extends AppModel {
	var $name = 'Schedule';
	var $validate = array();
	var $order = 'Schedule.due_date';
	var $recursive = 1;

	var $hasMany = array();
	var $belongsTo = array('Customer','User');
	var $hasAndBelongsToMany = array(
		'Service'
	);

	function beforeSave() {
		if(empty($this->data['Note']['id']))
			$this->data['Note']['user_id'] = $GLOBALS['current_user']['User']['id'];
		if(!empty($this->data['Note']['service_id']))
			$this->data['Note']['model'] = 'Service';
		return true;
	}
}
?>