<?php
class Customer extends AppModel {
    public $actsAs = array(
        'RemoveEmptyRelationships',
        'Joined',
        'Searchable.Searchable',
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        'Alkemann.Revision'=>array('limit'=>2)
    );
    public $displayField = 'company_name';
    public $findMethods = array(
        'listPotentialParents' => true,
        'firstCustomerView' => true,
        'allThroughService' => true
    );
    public $order = 'Customer.company_name';
    public $recursive = 0;
    public $validate = array();
    public $virtualFields = array(
        'text_status' => "(SELECT CASE Customer.status WHEN 1 THEN 'Active' WHEN 0 THEN 'Inactive' END)"
    );

    public static $status = array(
        'Cancelled'=>2,
        'Active'=>0,
        'Pending'=>1
    );

    public $hasMany = array(
        'Website' => array(
            'dependent' => true,
            'order' => 'Website.uri ASC'
        ),
        'Invoice' => array (
            'dependent' => true,
            'order' => 'Invoice.created DESC'
        ),
        'Referral' => array(
            'fields' => array('id','company_name','status'),
            'className' => 'Customer',
            'foreignKey' => 'customer_id',
            'dependent' => true,
            'order'=>'Referral.company_name ASC'
        ),
        'Service' => array(
            'dependent' => true,
            'order' => 'Service.title',
        ),
        'Note' => array(
            'dependent' => true,
            'order' => 'Note.created DESC'
        ),
        'Contact' => array(
            'dependent' => true,
            'order' => 'Contact.created DESC'
        )
    );
    public $belongsTo = array(
        'Reseller' => array(
            'fields' => array('id','company_name'),
            'className' => 'Customer',
            'foreignKey' => 'customer_id'
        )
    );

    public function cancel($customer_data=false) {
        if(!$customer_data) $customer_data = &$this->data;
        if(!empty($customer_data['Customer']['id']) && !empty($customer_data['Customer']['cancelled'])) {
            $customer_data['Customer']['status'] = self::$status['Cancelled'];
            $this->save($customer_data);
        } else {
            return false;
        }
    }

    public function indexData() {
        $index = array();
        $index[] = $this->data[$this->alias][$this->displayField];
        if (array_key_exists('id', $this->data[$this->alias])) {
            $id = $this->data[$this->alias]['id'];
            $Contact = ClassRegistry::init('Contact', true);
            $Website = ClassRegistry::init('Website', true);
            $contacts = $Contact->find('all', array(
                'conditions' => array('Contact.customer_id' => $id),
                'recursive' => -1
            ));
            $websites = $Website->find('all', array(
                'conditions' => array('Website.customer_id' => $id),
                'recursive' => -1
            ));
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    $index[] = sprintf("%s\n%s\n%s\n%s\n%s",
                        $contact['Contact']['name'],
                        $contact['Contact']['telephone'],
                        $contact['Contact']['mobile'],
                        $contact['Contact']['fax'],
                        $contact['Contact']['email'],
                        $contact['Contact']['address']
                    );
                }
            }
            if (!empty($websites)) {
                foreach ($websites as $website) {
                    $index[] = sprintf("%s",
                        $website['Website']['uri']
                    );
                }
            }
        }
        return join(". ", $index);
    }

    protected function _findListPotentialParents($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['conditions']['Customer.customer_id'] = null;
            $query['conditions'][] = array('NOT' => array('Customer.id' => $this->id));
            $query['fields'] = array('Customer.id','Customer.'.$this->displayField);
            $query['order'] = 'Customer.company_name ASC';
            $query['recursive'] = -1;
            return $query;
        } elseif ($state == 'after') {
            $newResults = array();
            foreach ($results as $result) {
                $newResults[$result['Customer']['id']] = $result['Customer'][$this->displayField];
            }
            return $newResults;
        }
    }

    protected function _findFirstCustomerView($state, $query, $results = array()) {
        if ($state == 'before') {
            $this->Service->unbindModel(array(
                'hasMany'=>array('Note'),
                'belongsTo'=>array('Customer','Website')
            ));
            $this->Invoice->unbindModel(array(
                'hasMany'=>array('Note'),
                'belongsTo'=>array('Service','Customer')
            ));
            $this->Website->unbindModel(array(
                'belongsTo'=>array('Customer')
            ));
            $this->Note->unbindModel(array(
                'belongsTo'=>array('Website','Customer','Service')
            ));
            $this->Referral->unbindModel(array(
                'hasMany'=>array('Referral','Invoice','Note','Contact'),
                'belongsTo'=>array('Reseller')
            ));
            $this->Contact->unbindModel(array(
                'belongsTo'=>array('Customer')
            ));
            $query['limit'] = 1;

            if (!is_array($query['conditions'])) {
                $query['conditions'] = array();
            }

            if (!empty($this->id) && !array_key_exists('Customer.id', $query['conditions'])) {
                $query['conditions']['Customer.id'] = $this->id;
            }
            return $query;
        } elseif ($state == 'after') {
            if (empty($results[0])) {
                return false;
            }

            $result = $results[0];
            if (array_key_exists('isAjax', $query) && $query['isAjax'] === true) {
                $result = $this->moveModelToRoot($result);
            }

            return $result;
        }
    }

    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        if (array_key_exists('byService', $extra) && $extra['byService']) {
            $serviceConditions = array();
            if (array_key_exists('serviceStatus', $extra)) {
                $serviceConditions['Service.status'] = $extra['serviceStatus'];
            }

            if (!array_key_exists('user_id', $extra)) {
                $serviceConditions['Service.user_id'] = User::getCurrent('id');
            } else {
                $serviceConditions['Service.user_id'] = $extra['user_id'];
            }

            $conditions['Customer.id'] = $this->getIdsThroughService($serviceConditions);

            $this->unbindModel(array(
                'hasMany' => array('Note', 'Invoice', 'Customer', 'Contact')
            ), false);
            $this->rebindJustServicesForUser($serviceConditions['Service.user_id']);
        }
        return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));
    }

    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        if (array_key_exists('byService', $extra) && $extra['byService']) {
            $serviceConditions = array();
            if (array_key_exists('serviceStatus', $extra)) {
                $serviceConditions['Service.status'] = $extra['serviceStatus'];
            }

            if (!array_key_exists('user_id', $extra)) {
                $serviceConditions['Service.user_id'] = User::getCurrent('id');
            } else {
                $serviceConditions['Service.user_id'] = $extra['user_id'];
            }

            if ($recursive < 1) {
                $recursive = 1;
            }

            $conditions['Customer.id'] = $this->getIdsThroughService($serviceConditions);
        }

        return $this->find('count', compact('conditions', 'recursive'));
    }

    public function getIdsThroughService($conditions) {
        $serviceConditions = array();

        if (!empty($conditions['user_id'])) {
            $serviceConditions['Service.user_id'] = $conditions['user_id'];
        } else {
            $serviceConditions['Service.user_id'] = User::getCurrent('id');
        }

        if (!empty($conditions['status'])) {
            $serviceConditions['Service.status'] = $conditions['status'];
        }

        $services = $this->Service->find('all', array(
            'recursive' => -1,
            'conditions' => $serviceConditions,
            'fields' => array('DISTINCT customer_id', 'modified')
        ));
        $in = array();

        foreach ($services AS $service) {
            $in[] = $service['Service']['customer_id'];
        }

        return $in;
    }

    public function rebindJustServicesForUser($user_id, $conditions = false) {
        $serviceConditions = array();
        $serviceConditions['Service.user_id'] = $user_id;
        if (is_array($conditions)) {
            $serviceConditions = array_merge($serviceConditions, $conditions);
        }
        $this->unbindModel(array(
            'hasMany' => array('Service')
        ), false);
        $this->bindModel(array(
            'hasMany' => array(
                'Service' => array(
                    'conditions' => $serviceConditions
                )
            )
        ), false);
    }

    protected function _findAllThroughService($state, $query, $results = array()) {
        if ($state == "before") {
            $serviceConditions = array();

            if (!empty($query['user_id'])) {
                $serviceConditions['user_id'] = $query['user_id'];
            } else {
                $serviceConditions['user_id'] = User::getCurrent('id');
            }

            if (array_key_exists('status', $query)) {
                $serviceConditions['Service.status'] = $query['status'];
            }

            if (!array_key_exists('recursive', $query)) {
                $query['recursive'] = 1;
            }

            if ($query['recursive'] <= 1) {
                $this->unbindModel(array(
                    'hasMany' => array('Note', 'Invoice', 'Customer', 'Contact')
                ), false);
            }

            $query['conditions']['Customer.id'] = $this->getIdsThroughService($serviceConditions);
            return $query;
        } elseif ($state == "after") {
            if (!empty($query['user_id'])) {
                $user_id = $query['user_id'];
            } else {
                $user_id = User::getCurrent('id');
            }
            if (array_key_exists('status', $query)) {
                $status = $query['status'];
            } else {
                $status = false;
            }
            $newResults = array();
            foreach ($results as $r => $result) {
                foreach ($result['Service'] as $s => $service) {
                    if ($service['user_id'] != $user_id) {
                        unset($result['Service'][$s]);
                        continue;
                    }
                    if (false !== $status) {
                        if ($service['status'] != $status) {
                            unset($result['Service'][$s]);
                            continue;
                        }
                    }
                }
                if(!empty($result['Service'])) {
                    $result['Service'] = array_values($result['Service']);
                    $newResults[] = $result;
                }
            }

            if (array_key_exists('isAjax', $query) && $query['isAjax']) {
                $newResults = $this->moveModelsToRoot($newResults);
            }
            return $newResults;
        }
    }

    public function afterFind($results, $primary) {
        if (!(isset($this->Ajax) || $this->Ajax)) {
            if (!preg_match('/^list/',$this->findQueryType)) {
                if ($primary) {
                    if ($this->recursive >= 0) {
                        $this->getResellerContacts($results);
                    }
                }
            }
        }
        return parent::afterFind($results, $primary);
    }

    private function getResellerContacts(&$results) {
        if (in_array('Contact', $this->hasMany)) {
            foreach ($results as $x => $result) {
                if (!array_key_exists($this->alias, $result)) {
                    $r =& $result;
                } else {
                    $r =& $result[$this->alias];
                }
                if (!array_key_exists('id', $r)) {
                    return;
                }
                $id = $r['id'];
                $reseller_id = $r['customer_id'];
                if ($reseller_id != null) {
                    $contacts = $this->Contact->find('all',array(
                        'conditions' => array('Contact.customer_id'=>$reseller_id),
                        'recursive' => -1
                    ));
                    if (!empty($contacts)) {
                        if (!array_key_exists('Contact', $results[$x])) {
                            $results[$x]['Contact'] = array();
                        }
                        foreach ($contacts as $contact) {
                            $contact['Contact']['role'] = 'Reseller';
                            array_push($results[$x]['Contact'],$contact['Contact']);
                        }
                    }
                }
            }
        }
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

    public function findResellers() {
        $results = array();
        $query = $this->query("SELECT DISTINCT customer_id AS id FROM customers AS Customer WHERE customer_id > 0 ORDER BY company_name ASC");
        foreach($query as $result) $results[] = $this->find(array('Customer.id'=>$result['Customer']['id']),null,null,-1);
        return $results;
    }

    public function getCustomerList() {
        $customers = $this->find('list',array(
            'fields' => array('Customer.id','Customer.company_name'),
            'conditions' => array('Customer.customer_id'=>null,'Customer.status'=>self::$status['Active']),
            'recursive'=>0,
            'order'=>'Customer.company_name ASC'
        ));
        return $customers;
    }

    public function reassessStatus($id) {
        $Customer = new Customer();
        $Customer->id = $id;
        $Customer->recursive = 1;
        if ($Customer->id && $customer = $Customer->read()) {
            $serviceStatus = false;
            if (!empty($customer['Service'])) {
                foreach ($customer['Service'] as $service) {
                    $serviceStatus = $serviceStatus || ( $service['status'] == 1 || $service['status'] == 2 );
                }
            }
            $serviceStatus = $serviceStatus || $Customer->hasActiveCustomers($id);
            $serviceStatus = (integer) $serviceStatus;
            $Customer->saveField('status', $serviceStatus, array('validate' => false, 'callbacks' => false));
        }
    }

    private function hasActiveCustomers($id) {
        return $this->find('count', array(
            'conditions' => array(
                'Customer.customer_id' => $id,
                'Customer.status >' => 0
            ),
            'recursive' => -1
        ));
    }
}
