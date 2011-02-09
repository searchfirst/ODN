<?php
class ServiceHelper extends AppHelper {

	var $status = array();

	function __construct($options = null) {
		parent::__construct($options);
		$this->status[0] = __('Cancelled',true);
		$this->status[1] = __('Pending',true);
		$this->status[2] = __('Active',true);
		$this->status[3] = __('Complete',true);
	}

	function status($status_num, $inflector = false) {
		$status = $this->status[$status_num];
		if ($inflector) {
			$status = Inflector::$inflector($status);
		}
		return $status;
	}

	function isActive($status_num) {
		return ($status_num == 2);
	}

	function isInactive($status_num) {
		return ($status_num == 0);
	}

	function flagTag($status_num, $tag = 'span') {
		$class = 'flag '.$this->status($status_num,'underscore');
		$status = $this->status($status_num);
		return sprintf('<%s class="%s">%s</%s>',$tag,$class,$status,$tag);
	}
}
