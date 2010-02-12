<?php
class Resource extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $name = 'Resource';
	var $validate = array();
	var $hasAndBelongsToMany = array("Article","Section","Product","Category");
}
?>