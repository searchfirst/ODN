<?php
class Group extends AppModel {
	var $name = 'Group';
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
				'message' => 'You must provide a username'
			)
		)
	);
	var $hasMany = array('User');

	function parentNode() {
		return null;
	}
	
	function getCurrent($session_data) {
		if(($user=$this->findById($session_data['User']['id'])) && (md5($user['User']['password']))==$session_data['User']['hash'])
			return $user;
		else return null;
	}

	function authenticate($user_data) {
		if(isset($user_data['User']) && !empty($user_data['User']['email']) && ($user=$this->findByEmail($user_data['User']['email']))) {
			if($user_data['User']['password']==$user['User']['password']) return $user;
			else return false;
		} else return false;
	}
}
?>
