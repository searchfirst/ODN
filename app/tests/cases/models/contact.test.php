<?php
/* Contact Test cases generated on: 2011-02-09 21:48:59 : 1297288139*/
App::import('Model', 'Contact');

class ContactTestCase extends CakeTestCase {
	var $fixtures = array('app.contact', 'app.customer', 'app.user', 'app.group', 'app.service', 'app.website', 'app.note', 'app.invoice', 'app.contacts_customer');

	function startTest() {
		$this->Contact =& ClassRegistry::init('Contact');
	}

	function endTest() {
		unset($this->Contact);
		ClassRegistry::flush();
	}

}
?>