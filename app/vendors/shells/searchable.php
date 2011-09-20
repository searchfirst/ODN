<?php
class SearchableShell extends Shell {
    var $uses = array(
        'Customer',
        'Service',
        'Website',
        'Contact',
        'Schedule',
        'Note'
    );

    function main() { $this->help(); }

    function help() {
        $this->out('cake searchable reindex: Reindex all tables');
        $this->hr();
    }

    function reindex() {
        //$contacts = $this->Contact->find('all');
        //foreach ($contacts as $contact) {
            //$this->Contact->recursive = -1;
            //$this->Contact->save($contact);
        //}
        //unset($contacts);
        $customers = $this->Customer->find('all', array('recursive' => -1));
        foreach ($customers as $customer) {
            $this->Customer->recursive = -1;
            $this->Customer->save($customer);
            $this->out(sprintf('Indexing: %s - %s', $customer['Customer']['id'], $customer['Customer']['company_name']));
        }
        unset($customers);
        //$services = $this->Service->find('all');
        //foreach ($services as $service) {
            //$this->Service->recursive = -1;
            //$this->Service->save($service);
        //}
        //unset($services);
        //$websites = $this->Website->find('all');
        //foreach ($websites as $website) {
            //$this->Website->recursive = -1;
            //$this->Website->save($website);
        //}
        //unset($websites);
        //$schedules = $this->Schedule->find('all');
        //foreach ($schedules as $schedule) {
            //$this->Schedule->save($schedule);
        //}
        //unset($schedules);
        //$notes = $this->Note->find('all');
        //foreach ($notes as $note) {
            //$this->Note->recursive = -1;
            //$this->Note->save($note);
        //}
        //unset($notes);
    }
}
