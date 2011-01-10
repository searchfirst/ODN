<?php
class Service extends AppModel {
	var $name = 'Service';
	var $order = 'Service.modified';
	var $recursive = 1;

	public static $status = array(
		'Cancelled'=>0,
		'Pending'=>1,
		'Active'=>2,
		'Complete'=>3
	);

	var $validate = array(
		'title'=>array(
			'rule'=>'notEmpty',
			'allowEmpty'=>false,
			'message'=>'You must choose or input a Service'
		),
		'website_id'=>array(
			'rule'=>'notEmpty',
			'allowEmpty'=>false,
			'message'=>'You must pick a website'
		),
		'user_id'=>array(
			'rule'=>'notEmpty',
			'allowEmpty'=>false,
			'message'=>'You must pick an employee'
		),
		'customer_id'=>array(
			'rule'=>'notEmpty',
			'allowEmpty'=>false
		)
	);

	var $hasMany = array('Note'=>array());
	var $belongsTo = array('Website','Customer','User');

	function beforeSave() {
		if(empty($this->id) && empty($this->data[$this->name]['joined']))
			$this->data[$this->name]['joined'] = strftime('%Y-%m-%d %T');
		return true;
	}
	
	function cancel() {
		if(!empty($this->data[$this->name]['id']) && !empty($this->data[$this->name]['cancelled'])) {
			$this->data[$this->name]['status'] = 2;
			$this->save($this->data);
		} else {
			return false;
		}
	}

}
?>