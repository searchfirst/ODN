<?php
class Group extends AppModel {
	//var $name = 'Group';
	var $order = 'Group.name';
	var $actsAs = array(
		'Acl'=>array(
			'type'=>'requester'
		)
	);
	var $recursive = 2;

	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a group name'
			)
		)
	);
	var $hasMany = array('User');

	function parentNode() {
		return null;
	}
}
