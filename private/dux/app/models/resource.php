<?php
class Resource extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $name = 'Resource';
	var $validate = array();
	var $hasAndBelongsToMany = array("Article","Section");
	
	function beforeValidate() {
		return true;
	}
	
	function beforeDelete() {
		if($resource=$this->findById($this->id))
			unlink($resource['Resource']['path'].$resource['Resource']['slug'].'.'.$resource['Resource']['extension']);
		return true;
	}
}
?>