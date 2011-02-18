<?php
class Invoice extends AppModel {
	var $validate = array();
	var $order = 'Invoice.created';
	var $recursive = 1;
	var $_findMethods = array('overdue' => true,'notOverdue' => true);

	var $hasMany = array('Note'=>array());
	var $belongsTo = array('Service','Customer');

	function __construct($id = false,$table = null,$ds = null) {
		parent::__construct($id,$table,$ds);
		$this->statuses = array(
			'due' => __('Due',true),
			'overdue' => __('Overdue',true),
			'cancelled' => __('Cancelled',true),
			'paid' => __('Paid',true)
		);
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

		$cur_date_str = strftime('%y%m%d');
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
		$this->setStatus($results,$primary);
		return $results;
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
