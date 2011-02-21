<?php
class Customer extends AppModel {
	var $order = 'Customer.company_name';
	var $recursive = 1;
	var $displayField = 'company_name';
	var $actsAs = array('Joined','Searchable.Searchable');

	public static $status = array(
		'Cancelled'=>2,
		'Active'=>0,
		'Pending'=>1
	);

	var $validate = array();

	var $hasMany = array(	
		'Website' => array(
			'dependent' => true,
		),
		'Invoice' => array (
			'dependent' => true,
			'order' => 'Invoice.created DESC'
		),
		'Referral' => array(
			'fields' => array('id','company_name','status'),
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'order'=>'Referral.company_name ASC',
			'dependent' => true,
		),
		'Service' => array(
			'dependent' => true,
			'className' => 'Service',
			'order' => 'Service.website_id',
			'group' => 'Service.website_id'
		),
		'Note' => array(
			'order' => 'Note.created DESC'
		),
	);
	var $belongsTo = array(
		'Reseller' => array(
			'fields' => array('id','company_name'),
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
		),
		'User' => array(
			'fields' => array('name','id')
		)
	);
	var $hasAndBelongsToMany = array(
		'Contact'
	);

	function cancel($customer_data=false) {
		if(!$customer_data) $customer_data = &$this->data;
		if(!empty($customer_data['Customer']['id']) && !empty($customer_data['Customer']['cancelled'])) {
			$customer_data['Customer']['status'] = self::$status['Cancelled'];
			$this->save($customer_data);
		} else {
			return false;
		}
	}

	function search($srch_string) {
		if(preg_match('/^\S{1,3}$/',$srch_string)) {
			$query = $this->query("SELECT id FROM customers WHERE company_name LIKE \"%$srch_string%\" ORDER BY company_name ASC");
		} else {
			$query = $this->query("SELECT id, MATCH(company_name,contact_name,telephone,fax,email,address,town,county,post_code) AGAINST('$srch_string' IN BOOLEAN MODE) AS score FROM customers WHERE MATCH(company_name,contact_name,telephone,fax,email,address,town,county,post_code) AGAINST('$srch_string' IN BOOLEAN MODE) ORDER BY company_name ASC");
		}
		$results = array();
		foreach($query as $result) $results[] = $this->find(array('Customer.id'=>$result['customers']['id']),null,null,-1);
		return $results;
	}

	function afterFind($results, $primary) {
		$this->setStatus($results, $primary);
		if ($primary) {
			$this->setInactiveLocations($results);
		}
		return $results;
	}

	private function setStatus(&$results, $primary) {
		if ($primary) {
			foreach ($results as $x => $result) {
				extract($this->getStatusWithService($result['Service']));
				$results[$x]['Customer']['text_status'] = $active ? __('Active',true) : ($pending ? __('Pending',true) : __('Inactive',true));
				$results[$x]['Customer']['status'] = $active ? 1 : ($pending ? 2 : 0);
			}
		}
	}

	private function __isAssoc($array) {
		return (is_array($array) && (count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))) )));
	}
	
	private function getStatusWithService($services) {
		$active = false;
		$pending = false;
		if (!empty($services)) {
			foreach ($services as $service) {
				$active = $active || ($service['status'] == 2); //Switch 2 and 1 once the changeover happens and 2 is pending 1 active
				$pending = $pending || ($service['status'] == 1);
			}
			if ($active) {
				$pending = false;
			}
		}
		return array('active'=>$active,'pending'=>$pending);
	}

	private function setInactiveLocations(&$results) {
		foreach ($results as $x => $result) {
			if (!empty($result['Service']) && !empty($result['Website'])) {
				$inactive_locations = array();
				foreach ($result['Website'] as $y => $website) {
					$active = false;
					foreach ($result['Service'] as $service) {
						$active = $active || ($service['website_id'] == $website['id']);
					}
					if (!$active) {
						$inactive_locations[] = $website;
						unset($results[$x]['Website'][$y]);
					}
				}
				$results[$x]['InactiveLocation'] = $inactive_locations;
				$results[$x]['Website'] = array_values($results[$x]['Website']);
			}
		}
	}

	function findResellers() {
		$results = array();
		$query = $this->query("SELECT DISTINCT customer_id AS id FROM customers AS Customer WHERE customer_id > 0 ORDER BY company_name ASC");
		foreach($query as $result) $results[] = $this->find(array('Customer.id'=>$result['Customer']['id']),null,null,-1);
		return $results;
	}

	function getCustomerList() {
		$customers = $this->find('list',array(
			'fields' => array('Customer.id','Customer.company_name'),
			'conditions' => array('Customer.customer_id'=>null,'Customer.status'=>self::$status['Active']),
			'recursive'=>0,
			'order'=>'Customer.company_name ASC'
		));
		return $customers;
	}
}
