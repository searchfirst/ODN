<?php
class Section extends AppModel {
    var $name = 'Section';
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
				"Article" => array(
					"dependent" => true,
					"order" => "order_by ASC"
				)
	);
	var $recursive = 2;

	function beforeSave() {
		if(empty($this->id))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}
}
?>