<?php
class StatusHelper extends Helper {
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