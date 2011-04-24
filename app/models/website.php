<?php
class Website extends AppModel {
	var $validate = array(
		'uri'=>array(
			'rule'=>'url',
			'allowEmpty'=>false,
			'message' => 'You must provide a web address'
		)
	);
	var $belongsTo = array("Customer");
	var $hasMany = array("Service");
	var $recursive = 2;
	var $actsAs = array('Searchable.Searchable');

	function beforeSave() {
		parent::beforeSave();
		if (isset($this->data['Website']['uri']))
			$this->data['Website']['uri'] = str_replace('http://','',$this->data['Website']['uri']);
		return true;
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
			print_r($conditions);
		} else return false;
	}
}
?>
