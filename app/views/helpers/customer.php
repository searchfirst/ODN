<?php
class CustomerHelper extends AppHelper {

	var $statuses = array();

	function __construct($options = null) {
		parent::__construct($options);
		$this->statuses[0] = __('Inactive',true);
		$this->statuses[1] = __('Active',true);
		$this->statuses[2] = __('Pending',true);
	}

	function status(&$customer, $inflector = false) {
		$status = $this->statuses[$customer['status']];
		if ($inflector) {
			$status = Inflector::$inflector($status);
		}
		return $status;
	}

	function isActive(&$customer) {
		return ($customer['status'] == 1);
	}

	function isInactive(&$customer) {
		return ($customer['status'] == 0);
	}

	function flagTag(&$customer, $tag = 'span') {
		$class = 'flag '.$this->status($customer['status'],'underscore');
		$status = $this->status($customer['status']);
		return sprintf('<%s class="%s">%s</%s>',$tag,$class,$status,$tag);
	}
}
