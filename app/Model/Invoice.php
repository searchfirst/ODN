<?php
class Invoice extends AppModel {
    public $validate = array();
    public $order = 'Invoice.created';
    public $recursive = 1;
    public $actsAs = array(
        'IntCaster' => array('cacheConfig' => 'lenore'),
        'Alkemann.Revision'
    );
    public $_findMethods = array('overdue' => true,'notOverdue' => true);

    public $hasMany = array('Note'=>array());
    public $belongsTo = array('Service','Customer');
    public $virtualFields = array();

    public function __construct($id = false,$table = null,$ds = null) {
        parent::__construct($id,$table,$ds);
        $this->statuses = array(
            'due' => __('Due',true),
            'overdue' => __('Overdue',true),
            'cancelled' => __('Cancelled',true),
            'paid' => __('Paid',true)
        );
        $this->virtualFields = array(
            'status' => "(SELECT CASE WHEN Invoice.cancelled IS TRUE THEN -1 WHEN Invoice.date_invoice_paid IS NULL AND Invoice.due_date < now() THEN 0 WHEN Invoice.due_date > now() THEN 1 WHEN Invoice.date_invoice_paid IS NOT NULL THEN 2 END)",
            'text_status' => "(SELECT CASE WHEN Invoice.cancelled IS TRUE THEN 'Cancelled' WHEN Invoice.date_invoice_paid IS NULL AND Invoice.due_date < now() THEN 'Overdue' WHEN Invoice.due_date > now() THEN 'Due' WHEN Invoice.date_invoice_paid IS NOT NULL THEN 'Paid' END)"
        );
    }

    function beforeSave() {
        parent::beforeSave();
        $this->addReferenceIfEmpty();
        return true;
    }

    function addReferenceIfEmpty() {
        if (!empty($this->data) && array_key_exists('Invoice',$this->data) && empty($this->data['Invoice']['reference'])) {
            $this->data['Invoice']['reference'] = $this->generateReference($this->data['Invoice']['customer_id']);
        }
    }

    function getVatTotal(&$invoice) {
        $c_vat_modifier = $invoice['Invoice']['vat_rate']/100;
        $vattotal = round($invoice['Invoice']['amount'] * $c_vat_modifier,2);
        if($invoice['Invoice']['vat_included']) return money_format('%.2n',$vattotal);
        else return 'N/A';
    }

    function getSubTotal(&$invoice) {
        return money_format('%.2n',$invoice['Invoice']['amount']);
    }

    function getGrandTotal(&$invoice) {
        $c_vat = (100 + $invoice['Invoice']['vat_rate'])/100;
        $vat_included = $invoice['Invoice']['vat_included'];
        $amount = $invoice['Invoice']['amount'];
        if($vat_included) $amount = round($amount * $c_vat,2);
        return money_format('%.2n',$amount);
    }

    function getVatRates() {
        $vat_array = array('Standard UK VAT (17.5%)'=>'17.5','Credit Crunch UK VAT (15%)'=>'15');
    }

    function generateReference($customer_id) {
        $invoice_string = '';
        $num_inv_this_cmr = $this->find('count',array('conditions'=>array('Customer.id'=>$customer_id)));

        $cur_date_str = strftime('%y%m');
        $unique_inv_count_str = str_pad(((string)$num_inv_this_cmr+1),3,'0',STR_PAD_LEFT);
        $customer_id_str = str_pad((string)$customer_id,4,'0',STR_PAD_LEFT);

        $invoice_string = $customer_id_str.'-'.$unique_inv_count_str.'-'.$cur_date_str;
        return $invoice_string;
    }

    function _findOverdue($state, $query, $results=array()) {
        if ($state == 'before') {
            if (!isset($query['order'])) {
                $query['order'] = 'Invoice.due_date DESC';
            }
            $conditions = array(
                'Invoice.due_date <' => date('Y-m-d'),
                'Invoice.cancelled' => false,
                'Invoice.date_invoice_paid' => null
            );
            $query['conditions'] = empty($query['conditions']) ? $conditions : array_merge($conditions, $query['conditions']);
            return $query;
        } elseif ($state == 'after') { return $results; }
    }

    function _findNotOverdue($state, $query, $results=array()) {
        if ($state == 'before') {
            if (!isset($query['order'])) {
                $query['order'] = 'Invoice.created DESC';
            }
            $conditions = array(
                'Invoice.due_date >' => date('Y-m-d'),
                'Invoice.cancelled' => false,
                'Invoice.date_invoice_paid' => null
            );
            $query['conditions'] = empty($query['conditions']) ? $conditions : array_merge($conditions, $query['conditions']);
            return $query;
        } elseif ($state == 'after') { return $results; }
    }

    function isCancelled(&$invoice) {
        return (bool) $invoice['cancelled'];
    }

    function isDue(&$invoice) {
        return ($invoice['date_invoice_paid'] == null);
    }

    function isPaid(&$invoice) {
        return ($invoice['date_invoice_paid'] != null);
    }

    function isOverdue(&$invoice) {
        if ($this->isCancelled($invoice) && !$this->isPaid($invoice)) {
            return false;
        } else {
            return strtotime($invoice['due_date']) - time() < 0;
        }
    }

    private function __isAssoc($array) {
        return (is_array($array) && (count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))) )));
    }

    function afterFind($results, $primary) {
        //$this->setStatus($results,$primary);
        $this->addPrimaryAddressToCustomer($results,$primary);
        return $results;
    }

    private function addPrimaryAddressToCustomer(&$results, $primary) {
        if ($primary) {
            foreach ($results as $a => $result) {
                if (!empty($result['Customer'])) {
                    $contact = $this->Customer->Contact->find('first',array(
                        'conditions' => array('Contact.customer_id' => $result['Customer']['id']),
                        'recursive' => -1,
                        'order' => 'Contact.created ASC'
                    ));
                    if (!empty($contact)) {
                        $results[$a]['Customer']['address'] = $contact['Contact']['address'];
                    }
                }
            }
        }
    }

    private function getStatusString($invoice) {
        if ($this->isCancelled($invoice)) {
            $status = $this->statuses['cancelled'];
            $class = 'cancelled';
        } elseif ($this->isPaid($invoice)) {
            $status = $this->statuses['paid'];
            $class = 'paid';
        } elseif ($this->isOverdue($invoice)) {
            $status = $this->statuses['overdue'];
            $class = 'overdue';
        } elseif ($this->isDue($invoice)) {
            $status = $this->statuses['due'];
            $class = 'due';
        }
        return $status;
    }

    private function setStatus(&$results,$primary) {
        if ($primary) {
            foreach ($results as $x => $result) {
                $results[$x]['Invoice']['text_status'] = $this->getStatusString($result['Invoice']);
            }
        } else {
            if (!empty($results['id'])) {
                $results['text_status'] = $this->getStatusString($result);
            } elseif (!empty($results)) {
                if (!$this->__isAssoc($results)) {
                    foreach ($results as $x => $result) {
                        if ($this->__isAssoc($result['Invoice'])) {
                            if (!empty($result['Invoice']) && isset($result['Invoice']['id'])) {
                                $results[$x]['Invoice']['text_status'] = $this->getStatusString($result['Invoice']);
                            }
                        } else {
                            foreach($result['Invoice'] as $y => $res) {
                                if (!empty($res['id'])) {
                                    $results[$x]['Invoice'][$y]['text_status'] = $this->getStatusString($res);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
