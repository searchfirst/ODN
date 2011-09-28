<?php
class UtilitiesController extends AppController {
    var $uses = array();

    function index() {
        $utilities = Configure::read('Dux.external_links');
        $this->set('utilities', $utilities);
    }
}
