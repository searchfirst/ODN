<?php
class ReassessShell extends Shell {
	var $uses = array('Customer');

	function main() { $this->help(); }

	function help() {
		$this->out('Reassess Service Status:');
		$this->hr();
	}

	function go() {
		$this->Customer->reassessStatus(950);
	}
}
