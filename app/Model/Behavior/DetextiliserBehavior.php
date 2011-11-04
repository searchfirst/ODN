<?php
class DetextiliserBehavior extends ModelBehavior {
    var $fields = array('description', 'content');
    var $Model;

    function setup (&$Model, $config = array()) {
        $this->Model =& $model;
        $this->_set($config);
    }

    function beforeSave(&$Model) {
        $this->deTextilise($Model);
        return true;
    }

    private function deTextilise(&$Model) {
        App::import('Vendor','textile');
        $textile = new Textile();
        $alias = $Model->alias;
        $fields = $this->fields;
        if (is_array($Model->data) && array_key_exists($alias,$Model->data)) {
            foreach ($fields as $field) {
                if (array_key_exists($field,$Model->data[$alias])) {
                    $Model->data[$alias][$field] = $textile->deTextile($Model->data[$alias][$field]);
                }
            }
        }
    }
}
