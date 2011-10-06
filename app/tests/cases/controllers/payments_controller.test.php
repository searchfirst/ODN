<?php
/* Payments Test cases generated on: 2011-09-29 15:48:21 : 1317307701*/
App::import('Controller', 'Payments');

class TestPaymentsController extends PaymentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PaymentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.payment', 'app.user', 'app.group', 'app.customer', 'app.website', 'app.service', 'app.note', 'app.invoice', 'app.contact');

	function startTest() {
		$this->Payments =& new TestPaymentsController();
		$this->Payments->constructClasses();
	}

	function endTest() {
		unset($this->Payments);
		ClassRegistry::flush();
	}

}
