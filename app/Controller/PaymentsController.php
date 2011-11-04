<?php
class PaymentsController extends AppController {
    var $helpers = array(
        'Html',
        'Form',
        'Time',
        'T',
        'Paginator'
    );
    var $paginate = array(
        'limit' => 10,
        'order' => array('Customer.company_name' => 'ASC'),
        'recursive' => 0
    );
    var $primaryModel = "Payment";

    function index() {
        $conditions = array();
        $doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
        if ($this->RequestHandler->isAjax()) { $this->paginate['limit'] = 10; }
        if (!empty($this->params['url']['customer_id'])) {
            $customer_id = $this->params['url']['customer_id'] == 'null' ? null : $this->params['url']['customer_id'];
            $conditions['Customer.customer_id'] = $customer_id;
        }
        if (array_key_exists('filter',$this->params['url'])) {
            $conditions['Customer.company_name LIKE'] = $this->params['url']['filter'] . '%';
            $title_for_layout = sprintf('Customers in %s', $this->params['url']['filter']);
        } else {
            $title_for_layout = 'Customers';
        }
        if ($doPaginate) {
            $customers = $this->paginate('Customer',$conditions);
        } else {
            $this->Customer->recursive = 0;
            $customers = $this->Customer->find('all',array('conditions' => $conditions));
        }
        $this->set(compact('title_for_layout', 'customers', 'doPaginate'));
    }

    function view() {
    }

    function add() {
    }

    function edit() {
    }

    function delete() {
    }
}
