<?php
class Contact extends AppModel {
    public $actsAs = array(
        'Detextiliser'=>array('fields'=>array('address')),
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        //'Alkemann.Revision'
    );
    public $belongsTo = array('Customer');
    public $findMethods = array('allRelated' => true);

    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        if (!empty($conditions['Contact.customer_id'])) {
            $conditions = $this->addResellerToCustomerCondition($conditions);
        }
        return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
    }

    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        if (!empty($conditions['Contact.customer_id'])) {
            $conditions = $this->addResellerToCustomerCondition($conditions);
        }
        return $this->find('count', compact('conditions', 'recursive'));
    }

    protected function _findAllRelated($state, $query, $results = array()) {
        if ($state == "before") {
            $query['conditions'] = $this->addResellerToCustomerCondition($query['conditions']);
            return $query;
        } else if ($state == "after") {
            return $results;
        }
    }

    protected function addResellerToCustomerCondition($conditions) {
        if (!empty($conditions['customer_id'])) {
            $customer_id =& $conditions['customer_id'];
            $key = 'customer_id';
        } else if (!empty($conditions['Contact.customer_id'])) {
            $customer_id =& $conditions['Contact.customer_id'];
            $key = 'Contact.customer_id';
        } else if (!empty($conditions['Customer.id'])) {
            $customer_id =& $conditions['Customer.id'];
            $key = 'Customer.id';
        }

        if (isset($customer_id)) {
            $this->Customer->id = $customer_id;
            $reseller_id = $this->Customer->field('customer_id');
            $customer = $this->Customer->find('first', array(
                'conditions' => array('Customer.id' => $reseller_id),
                'recursive' => -1
            ));
            if ($customer) {
                $conditions[$key] = array($customer_id, $customer['Customer']['id']);
            }
        }

        return $conditions;
    }
}
