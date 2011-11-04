<?php
class InvoiceHelper extends Helper {
	var $helpers = array('Time');

	function __construct($options = null) {
		parent::__construct($options);
		$this->statuses = array(
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

	function flagTag(&$invoice, $tag = 'span') {
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
		return sprintf('<%s class="flag %s">%s</%s>',$tag,$class,$status,$tag);
	}

	function invoiceSummary(&$invoice) {
		$flagTag = $this->flagTag($invoice);
		$amount = money_format('%n',$invoice['amount']);
		$issued = $this->Time->format('d/m/Y',$invoice['created']);
		$summary = sprintf('%s %s. %s: %s & ',$flagTag,$amount,__('Issued',true),$issued);
		if ($this->isPaid($invoice)) {
			$paid = $this->Time->format('d/m/Y',$invoice['date_invoice_paid']);
			$summary .= sprintf('%s: %s',__('Paid',true),$paid);
		} else {
			$due = $this->Time->format('d/m/Y',$invoice['due_date']);
			$summary .= sprintf('%s: %s',__('Due',true),$due);
		}
		return $summary;
	}
}
