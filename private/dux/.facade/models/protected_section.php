<?php
class ProtectedSection extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $name = 'ProtectedSection';
	var $validate = array(
		'title'			=>	VALID_NOT_EMPTY,
		'description'	=>	VALID_NOT_EMPTY
	);
	var $hasAndBelongsToMany = array(
				"Resource" => array(
					"dependent" => true,
					"conditions" => "Resource.type=1",
					"order" => "order_by ASC"
				),
				"Decorative" => array(
					"dependent" => true,
					"className" => "Resource",
					"conditions" => "Decorative.type=0",
					"order" => "order_by ASC"
				),
				"Downloadable" => array(
					"dependent" => true,
					"className" => "Resource",
					"conditions" => "Downloadable.type=2",
					"order" => "order_by ASC"
				)
	);
	var $hasMany = array(
				"ProtectedItem" => array(
					"dependent" => true,
					"order" => "order_by ASC"
				)
	);
	var $recursive = 2;

	function authenticate($username,$password_hash) {
		if(empty($username) || empty($password_hash))
			return false;
		else {
			$user_auth_data = $this->findByTitle($username);
			if(!empty($user_auth_data) && ($password_hash === md5($user_auth_data['ProtectedSection']['password'])))
				return $user_auth_data;
			else
				return false;
		}
	}

}
?>