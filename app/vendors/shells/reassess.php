<?php
class ReassessShell extends Shell {
	var $uses = array('Customer');

	function main() { $this->help(); }

	function help() {
		$this->out('Reassess Service Status:');
		$this->hr();
	}

	function go() {
		$this->Customer->recursive = 1;
		$customers = $this->Customer->find('all');
		foreach ($customers as $customer) {
			$this->out($customer['Customer']['company_name']);
			$service_status = false;
			if (!empty($customer['Service'])) {
				foreach($customer['Service'] as $service) {
					$service_status = $service_status || ( $service['status'] > 0 );
				}
			}
			$this->Customer->id = $customer['Customer']['id'];
			$this->Customer->saveField('service_status',(integer) $service_status);
		}
	}
}
