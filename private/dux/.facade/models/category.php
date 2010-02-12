<?php
class Category extends AppModel {
    var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
    var $name = 'Category';
    var $validate = array(
		'title'			=> VALID_NOT_EMPTY,
		'description'	=> VALID_NOT_EMPTY
	);
	var $hasAndBelongsToMany = array(
							"Resource" => array(
								"dependent" => true,
								"conditions" => "Resource.type=1",
								"order" => "Resource.order_by ASC"
							),
							"Decorative" => array(
								"dependent" => true,
								"className" => "Resource",
								"conditions" => "Decorative.type=0",
								"order" => "Decorative.order_by ASC"
							),
							"Downloadable" => array(
								"dependent" => true,
								"className" => "Resource",
								"conditions" => "Downloadable.type=2",
								"order" => "Downloadable.order_by ASC"
							)
	);
    var $hasMany = array(	"Product" => array(
								"dependent" => true,
								"conditions" => "Product.draft=0",
								"order" => "Product.order_by ASC"
							),
							"Subcategories" => array(
								"className" => "Category",
								"foreignKey" => "category_id",
								"dependent" => true,
								"order" => "Subcategories.order_by ASC"
							)
	);
	var $belongsTo = array(	"Parent" => array(
								"className" => "Category",
								"foreignKey" => "category_id"
							)
	);
	var $order = 'Category.order_by ASC';
	var $recursive = 2;

	function beforeSave() {
		if(empty($this->id))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		if(!isset($this->data[$this->name]['category_id']))
			$this->data[$this->name]['category_id'] = $this->field('category_id','Category.id = '.$this->id);
		return true;
	}
}
?>