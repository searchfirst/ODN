<?php
class User extends AppModel {
	var $name = 'User';
	var $order = 'User.name';
	//var $actsAs = array('Authenticate'=>array());
	var $recursive = 1;

	var $validate = array(
		'title'			=> VALID_NOT_EMPTY,
		'description'	=> VALID_NOT_EMPTY
	);
	var $hasMany = array(	
		"Customer" => array(
			'order' => 'Customer.modified DESC'
		),
		'Service' => array(
			'order' => 'Service.modified DESC'
		)
	);
	var $hasAndBelongsToMany = array(
		"TechnicalCustomer"=>array('with'=>'Service','className'=>'Customer','order'=>'TechnicalCustomer.status ASC'),
		'Website'=>array('with'=>'Service','className'=>'Website'),
		"Group"=>array()
	);
		
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
}
?>