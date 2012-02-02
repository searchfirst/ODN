<?php
class DetextiliserBehavior extends ModelBehavior {
    public $__defaultSettings = array(
        'fields' => array('description', 'content')
    );

    public function setup(&$Model, $settings = array()) {
        $this->settings[$Model->alias] = $settings + $this->__defaultSettings;
    }

    public function beforeSave(&$Model) {
        $this->deTextilise($Model);
        return true;
    }

    private function deTextilise(&$Model) {
        App::import('Vendor','textile');
        $textile = new Textile();
        $alias = $Model->alias;
        $fields = $this->settings[$Model->alias]['fields'];
        if (is_array($Model->data) && array_key_exists($alias,$Model->data)) {
            foreach ($fields as $field) {
                if (array_key_exists($field,$Model->data[$alias])) {
                    $Model->data[$alias][$field] = $textile->deTextile($Model->data[$alias][$field]);
                }
            }
        }
    }
}
