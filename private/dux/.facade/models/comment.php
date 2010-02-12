<?php
class Comment extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $name = 'Comment';
	var $validate = array(
		'description'	=>	VALID_NOT_EMPTY,
		'email' => VALID_EMAIL
	);

	var $belongsTo = array("Article");
	
	function beforeValidate() {
		if(!empty($this->data['Comment']['uri']) && !preg_match('|^http://|',$this->data['Comment']['uri']))
			$this->data['Comment']['uri'] = 'http://'.$this->data['Comment']['uri'];
		return true;
	}
}
?>