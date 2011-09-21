<?php
class Contact extends AppModel {
	var $belongsTo = array('Customer');
	var $actsAs = array(
		'Detextiliser'=>array('fields'=>array('address')),
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
		'Searchable.Searchable',
		'Alkemann.Revision'
	);
	var $_findMethods = array('allRelated' => true);
	function _findAllRelated($state, $query, $results = array()) {
		if ($state == "before") {
			$query['conditions'] = $this->generateRelatedConditions($query['conditions']);
			return $query;
		} else if ($state == "after") {
			return $results;
		}
	}

	function generateRelatedConditions($conditions) {
		if (!empty($conditions['Customer.id'])) {
			$customer_id = $conditions['Customer.id'];
		} else if (!empty($conditions['Contact.customer_id'])) {
			$customer_id = $conditions['Contact.customer_id'];
		}
		if (!empty($customer_id)) {
			$customer = $this->Customer->find('first',array(
				'conditions' => array('Customer.id'=>$customer_id),
				'recursive' => -1
			));
			if (!empty($customer)) {
				$parentId = $customer['Customer']['customer_id'];
				$conditions = array('OR' => array(
					$conditions,
					'Customer.id' => $parentId
				));
			}
		}
		return $conditions;
	}
}
