<?php
class CustomerHelper extends AppHelper {
    public $helpers = array('Html');

    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
    }

    public function flag(&$customer, $element = 'span') {
        $tag = $this->Html->tag($element, $customer['text_status'], array(
            'class' => join(' ', array('flag', Inflector::underscore($customer['text_status'])))
        ));
        return $tag;
    }
}
