<?php
class Customer extends AppModel {
    var $actsAs = array(
        'RemoveEmptyRelationships',
        'Joined',
        'Searchable.Searchable',
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        'Alkemann.Revision'=>array('limit'=>2)
    );
    var $displayField = 'company_name';
    var $_findMethods = array('listPotentialParents' => true);
    var $order = 'Customer.company_name';
    var $recursive = 1;
    var $validate = array();
    var $virtualFields = array(
        'text_status' => '(SELECT CASE Customer.status WHEN 1 THEN "Active" WHEN 0 THEN "Inactive" END)'
    );

    public static $status = array(
        'Cancelled'=>2,
        'Active'=>0,
        'Pending'=>1
    );

    var $hasMany = array(   
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
    var $belongsTo = array(
        'Reseller' => array(
            'fields' => array('id','company_name'),
            'className' => 'Customer',
            'foreignKey' => 'customer_id'
        ),
        'User' => array(
            'fields' => array('name','id')
        )
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

    function indexData() {
        $index = array();
        $index[] = $this->data[$this->alias][$this->displayField];
        $id = $this->data[$this->alias]['id'];
        $this->read();
        $contacts = $this->Contact->find('all', array(
            'conditions' => array('Contact.customer_id' => $id),
            'recursive' => -1
        ));
        $websites = $this->Website->find('all', array(
            'conditions' => array('Website.customer_id' => $id),
            'recursive' => -1
        ));
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $Contact = new Contact();
                $Contact->id = $contact['Contact']['id'];
                $Contact->recursive = -1;
                $Contact->read();
                $index[] = $Contact->processData();
            }
        }
        if (!empty($websites)) {
            foreach ($websites as $website) {
                $Website = new Website();
                $Website->id = $website['Website']['id'];
                $Website->recursive = -1;
                $Website->read();
                $index[] = $Website->processData();
            }
        }
        return join("\n", $index);
    }

    function _findListPotentialParents($state, $query, $results = array()) {
        if ($state == "before") {
            $query['conditions'] = array('Customer.customer_id'=>null,array('NOT'=>array('Customer.id'=>$this->id)));
            $query['fields'] = array('Customer.id','Customer.'.$this->displayField);
            $query['order'] = 'Customer.company_name ASC';
            $query['recursive'] = -1;
            return $query;
        } elseif ($state == "after") {
            $newResults = array();
            foreach ($results as $result) {
                $newResults[$result['Customer']['id']] = $result['Customer'][$this->displayField];
            }
            return $newResults;
        }
    }

    function afterFind($results, $primary) {
        $results = parent::afterFind($results, $primary);
        if (!preg_match('/^list/',$this->findQueryType)) {
            if ($primary) {
                if ($this->recursive >= 0) {
                    $this->getResellerContacts($results);
                }
            }
        }
        return $results;
    }

    private function getResellerContacts(&$results) {
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

    function reassessStatus($id) {
        $Customer = new Customer();
        $Customer->id = $id;
        if ($Customer->id && $customer = $Customer->read()) {
            $serviceStatus = false;
            if (!empty($customer['Service'])) {
                foreach ($customer['Service'] as $service) {
                    $serviceStatus = $serviceStatus || ( $service['status'] > 0 );
                }
            }
            $serviceStatus = $serviceStatus || $this->hasActiveCustomers($id);
            $serviceStatus = (integer) $serviceStatus;
            $Customer->saveField('status', $serviceStatus, false);
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
