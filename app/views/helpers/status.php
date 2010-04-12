<?php
class StatusHelper extends Helper {
	
	var $statuses = array(
		'Service'=>array(
			0=>'Cancelled',
			1=>'Pending',
			2=>'Active',
			3=>'Complete'
		),
		'Customer'=>array(
			0=>'Active',
			1=>'Pending', //Unused
			2=>'Cancelled'
		),
		'Employee'=>array(
			0=>'Employed',
			1=>'Resigned'
		)
	);
	
	var $schedules = array(
		'Service'=>array(
			0=>'Monthly',
			1=>'Quarterly',
			2=>'Annual',
			3=>'Manual'
		)
	);
		
	var $service_schedule = array(
		0=>'Monthly',
		1=>'Quarterly',
		2=>'Annual',
		3=>'Manual'
	);
	
	function getStatusString($model,$status) {
		if(!empty($this->statuses[$model][$status])) {
			return $this->statuses[$model][$status];
		} else {
			return "Error Retrieving Value";
		}
	}
	
	function getScheduleString($model,$schedule) {
		if(!empty($this->schedules[$model][$schedule])) {
			return $this->schedules[$model][$schedule];
		} else {
			return "Error Retrieving Value for $schedule";
		}
	}
	
	function getDueString($invoice) {
		$now = time();
		$date_due = $invoice['Invoice']['due_date'];
		$date_paid = $invoice['Invoice']['date_invoice_paid'];
		if($date_paid) {
			return "Paid";
		} elseif(strtotime($date_due) < $now) {
			return "Overdue";
		} else {
			return "Pending";
		}
	}
	
	function getLcStatusString($model,$status) {
		return Inflector::underscore($this->getStatusString($model,$status));
	}
	
	function getLcScheduleString($model,$schedule) {
		return Inflector::underscore($this->getScheduleString($model,$schedule));
	}
	
	function getLcDueString($invoice) {
		return Inflector::underscore($this->getDueString($invoice));
	}

	function serviceStatus($status) {
		switch($status) {
			case 0:
				return "Cancelled";
				break;
			case 1:
				return "Pending";
				break;
			case 2:
				return "Active";
				break;
			case 3:
				return "Complete";
				break;
			default:
				return "Erroneous Value";
				break;
		}
	}

	function customerStatus($status) {
		switch($status) {
			case 0:
				return "Active";
				break;
			case 1:
				return "Pending";
				break;
			case 2:
				return "Cancelled";
				break;
			default:
				return "Erroneous Value";
				break;
		}
	}
	
	
	function serviceSchedule($schedule) {
		switch($schedule) {
			case 0:
				return "Monthly";
				break;
			case 1:
				return "Quarterly";
				break;
			case 2:
				return "Annual";
				break;
			case 3:
				return "Manual";
				break;
			default:
				return "Unspecified";
				break;
		}
	}
}
?>