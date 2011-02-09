<?php
class Service extends AppModel {
	var $order = 'Service.modified';
	var $actsAs = array('Joined','Searchable.Searchable');
	var $recursive = 1;
	var $_findMethods = array('customers' => true);

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

	var $hasMany = array(
		'Note'
	);
	var $belongsTo = array(
		'Website',
		'Customer',
		'User'
	);

	function cancel() {
		if(!empty($this->data[$this->name]['id']) && !empty($this->data[$this->name]['cancelled'])) {
			$this->data[$this->name]['status'] = 2;
			$this->save($this->data);
		} else {
			return false;
		}
	}

	function _findCustomers($state, $query, $results = array()) {
		if ($state == "before") {
			$query['order'] = 'Customer.company_name ASC';
			return $query;
		} elseif ($state == "after") {
			$newResults = array();
			$i = -1;
			$p = '';
			foreach ($results as $result) {
				if ($result['Customer']['id'] != $p) {
					$i++;
					$p = $result['Customer']['id'];
					$newResults[$i] = array(
						'Customer'=>$result['Customer'],
						'Service'=>array()
					);
				}
				$newResults[$i]['Service'][] = $result['Service'];
			}
			return $newResults;
		}
	}
}
