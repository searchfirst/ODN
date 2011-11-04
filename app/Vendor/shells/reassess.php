<?php
class ReassessShell extends Shell {
    var $uses = array('Customer');

    function main() { $this->help(); }

    function help() {
        $this->out('Reassess Service Status:');
        $this->hr();
    }

    function go() {
        $customers = $this->Customer->find('all', array(
            'recursive' => -1,
            'fields' => array('id')
        ));
        foreach ($customers as $customer) {
            $this->Customer->reassessStatus($customer['Customer']['id']);
        }
    }
}
