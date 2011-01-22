<?php
class User extends AppModel {
	var $order = 'User.name';
	var $displayField = 'username';
	public static $currentUser = array();
	var $actsAs = array(
		'Acl'=>array(
			'type'=>'requester'
		)
	);
	var $recursive = 1;

	var $validate = array(
		'username' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a username'
			)
		)
	);
	var $hasMany = array(	
		"Customer" => array(
			'order' => 'Customer.modified DESC'
		),
		'Service' => array(
			'order' => 'Service.modified DESC'
		)
	);
	var $belongsTo = array(
		'Group'
	);
	var $hasAndBelongsToMany = array(
		"TechnicalCustomer"=>array('with'=>'Service','className'=>'Customer','order'=>'TechnicalCustomer.status ASC'),
		'Website'=>array('with'=>'Service','className'=>'Website'),
	);
		
	public static function getCurrent($key=null) {
		if(!empty(self::$currentUser) ){
			if(!$key) {
			return self::$currentUser;
			} elseif(!empty(self::$currentUser['User'][$key])) {
				return self::$currentUser['User'][$key];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public static function setCurrent($user) {
		self::$currentUser = $user;
		return true;
	}

	function inGroup($group_name,$user=null) {
		if(!$user) {
			global $current_user;
			$user = $current_user;
		}
		if(empty($user['Group'])) return false;
		else
			foreach($user['Group'] as $group)
				if($group['name']==$group_name) return true;
		return false;
	}

	function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['group_id'])) {
			$groupId = $this->data['User']['group_id'];
		} else {
			$groupId = $this->field('group_id');
		}
		if (!$groupId) {
			return null;
		} else {
			return array('Group' => array('id' => $groupId));
		}
	}

	function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}
}
