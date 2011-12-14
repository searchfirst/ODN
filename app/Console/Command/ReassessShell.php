<?php
class ReassessShell extends AppShell {
    var $uses = array('Customer');

    function main() { $this->help(); }

    function help() {
        $this->out('Reassess Service Status:');
        $this->hr();
    }

    function go() {
        $customers = $this->Customer->find('all', array( 'recursive' => -1));
        foreach ($customers as $customer) {
            $this->out($customer['Customer']['company_name']);
            $this->Customer->reassessStatus($customer['Customer']['id']);
            $this->out(' ');
        }
    }
}
