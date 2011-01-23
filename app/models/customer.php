<?php
class Customer extends AppModel {
	var $order = 'Customer.company_name';
	var $recursive = 2;
	var $actsAs = array('Joined','Searchable.Searchable');

	public static $status = array(
		'Cancelled'=>2,
		'Active'=>0,
		'Pending'=>1
	);

	var $validate = array();

	var $hasMany = array(	
		"Website" => array(
			"dependent" => true,
		),
		"Invoice" => array (
			"dependent" => true,
			'order' => 'Invoice.created DESC'
		),
		"Referral" => array(
			"className" => "Customer",
			"foreignKey" => "customer_id",
			"order"=>"Referral.company_name ASC",
			"dependent" => true,
		),
		"Service" => array(
			'dependent' => true,
			'className' => 'Service',
			'order' => 'Service.website_id',
			'group' => 'Service.website_id'
		),
		'Note' => array(
			'order' => 'Note.created DESC'
		),
/*		'Agent'=>array(
			'className' => 'User',
			'foreignKey' => 'id',
			'finderQuery' => 'SELECT DISTINCT Agent.id,Agent.name,Service.customer_id FROM services Service JOIN users Agent ON Service.user_id=Agent.id JOIN customers Customer ON Service.customer_id={$__cakeID__$} ORDER BY Agent.id'
		)*/
	);
	var $belongsTo = array(
		"Reseller" => array(
			"className" => "Customer",
			"foreignKey" => "customer_id",
		),
		'User'
	);
	var $hasAndBelongsToMany = array(
		'TechnicalUser'=>array('with'=>'Service','className'=>'User')
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
	
	function findResellers() {
		$results = array();
		$query = $this->query("SELECT DISTINCT customer_id AS id FROM customers AS Customer WHERE customer_id > 0 ORDER BY company_name ASC");
		foreach($query as $result) $results[] = $this->find(array('Customer.id'=>$result['Customer']['id']),null,null,-1);
		return $results;
	}
	
	function findAllWithService($conditions=null,$fields=null,$order=null,$limit=null,$page=null,$recursive=null,$user=null) {
		if(!$user) {
			global $current_user;
			$user = &$current_user;
		}
		$standard_find = $this->findAll($conditions,$fields,$order,$limit,$page,$recursive);
		$id_filter = array();
		foreach($standard_find as $standard_find_item)
			$id_filter[] = $standard_find_item['Customer']['id'];
		foreach($user['TechnicalCustomer'] as $technical_customer_item)
			if(!in_array($technical_customer_item['id'],$id_filter))
				$standard_find[]['Customer'] = $technical_customer_item;
		return $standard_find;			
	}
	
	function findWithService($conditions=null,$fields=null,$order=null,$limit=null,$page=null,$recursive=null,$user=null) {
		if(!$user) {
			global $current_user;
			$user = &$current_user;
		}
		if($standard_find = $this->find($conditions,$fields,$order,$limit,$page,$recursive)) {
			return $standard_find;
		} elseif(!empty($conditions['Customer.id'])) {
			$find_id = $conditions['Customer.id'];
			foreach($user['TechnicalCustomer'] as $technical_customer)
				if($find_id==$technical_customer['id']) return array('Customer'=>$technical_customer);
		} else return false;
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
?>
