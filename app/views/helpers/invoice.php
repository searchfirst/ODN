<?php
class InvoiceHelper extends Helper {
	var $helpers = array('Time');

	function __construct($options = null) {
		parent::__construct($options);
		$this->status = array(
			'due' => __('Due',true),
			'overdue' => __('Overdue',true),
			'cancelled' => __('Cancelled',true),
			'paid' => __('Paid',true)
		);
	}

	function isCancelled(&$invoice) {
		return (bool) $invoice['cancelled'];
	}

	function isDue(&$invoice) {
		return (bool) $invoice['date_invoice_paid'];
	}

	function isPaid(&$invoice) {
		return ($invoice['date_invoice_paid'] != null);
	}

	function isOverdue(&$invoice) {
		if ($this->isCancelled($invoice) && !$this->isPaid($invoice)) {
			return false;
		} else {
			return strtotime($invoice['due_date']) - time() > 0;
		}
	}

	function flagTag(&$invoice, $tag = 'span') {
		if ($this->isCancelled($invoice)) {
			$status = $this->status['cancelled'];
			$class = 'cancelled';
		} elseif ($this->isPaid($invoice)) {
			$status = $this->status['paid'];
			$class = 'paid';
		} elseif ($this->isOverdue($invoice)) {
			$status = $this->status['overdue'];
			$class = 'overdue';
		} elseif ($this->isDue($invoice)) {
			$status = $this->status['due'];
			$class = 'due';
		}
		return sprintf('<%s class="flag %s">%s</%s>',$tag,$class,$status,$tag);
	}

	function invoiceSummary(&$invoice) {
		$flagTag = $this->flagTag($invoice);
		$amount = money_format('%n',$invoice['amount']);
		$issued = $this->Time->niceShort($invoice['created']);
		$summary = sprintf('%s %s. %s: %s & ',$flagTag,$amount,__('Issued',true),$issued);
		if ($this->isPaid($invoice)) {
			$paid = $this->Time->niceShort($invoice['date_invoice_paid']);
			$summary .= sprintf('%s: %s',__('Paid',true),$paid);
		} else {
			$due = $this->Time->niceShort($invoice['due_date']);
			$summary .= sprintf('%s: %s',__('Due',true),$due);
		}
		return $summary;
	}
}
