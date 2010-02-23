<?php
class Contact extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $useTable = false;
	var $name = 'Contact';

	var $validate = array(
		'name'			=> VALID_NOT_EMPTY,
		'email'			=> VALID_EMAIL,
		'telephone'		=> '/[0-9\s]*/i',
		'enquiry'		=> VALID_NOT_EMPTY
	);
	
	function loadInfo() {return new Set(array(array()));}  //temp hack until fixed
	
	function beforeSave() {return true;}
	
}
?>