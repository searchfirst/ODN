<?php
class Group extends AppModel {
	var $name = 'Group';
	var $order = 'Group.name';
	//var $actsAs = array('Authenticate'=>array());
	var $recursive = 2;

	var $validate = array(
		'name'=>VALID_NOT_EMPTY
	);
	var $hasMany = array();
	var $hasAndBelongsToMany = array(
		'User'=>array('conditions'=>array('User.status'=>USER_STATUS_EMPLOYED))
	);

	function beforeSave() {return true;}
	
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