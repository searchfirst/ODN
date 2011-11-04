<?php
class Schedule extends AppModel {
	var $validate = array();
	var $order = 'Schedule.due_date';
	var $recursive = 1;
	var $actsAs = array('Searchable.Searchable');

	var $hasMany = array('Invoice');
	var $belongsTo = array('Customer','User');
	var $hasAndBelongsToMany = array(
		'Service'
	);
}
?>
