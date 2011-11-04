<?php
/* Payment Test cases generated on: 2011-09-29 15:40:39 : 1317307239*/
App::import('Model', 'Payment');

class PaymentTestCase extends CakeTestCase {
	var $fixtures = array('app.payment');

	function startTest() {
		$this->Payment =& ClassRegistry::init('Payment');
	}

	function endTest() {
		unset($this->Payment);
		ClassRegistry::flush();
	}

}
