<?php
class Note extends AppModel {
	var $name = 'Note';
	var $validate = array();
	var $order = 'Note.modified';
	var $recursive = 2;

	var $hasMany = array();
	var $belongsTo = array('Website','Customer','User','Service');

	function beforeSave() {
		if(empty($this->data['Note']['id']))
			$this->data['Note']['user_id'] = $GLOBALS['current_user']['User']['id'];
		if(!empty($this->data['Note']['service_id']))
			$this->data['Note']['model'] = 'Service';
		return true;
	}
}
?>