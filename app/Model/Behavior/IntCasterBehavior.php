<?php
class IntCasterBehavior extends ModelBehavior {
    public $__defaultSettings = array(
        'cacheConfig' => 'default'
    );
    public $fields = array();
    public $settings = array();

    function setup(&$Model, $settings = array()) {
        $this->settings[$Model->alias] = $settings + $this->__defaultSettings;
        $this->getCachedFields($Model);
    }

    function afterFind(&$Model, $results, $primary) {
        $alias = $Model->alias;
        if ($primary && !empty($results[0])) {
            foreach ($results as $x =>$result) {
                foreach ($this->fields[$alias] as $field) {
                    if (array_key_exists($alias, $result) && array_key_exists($field, $result[$alias]) && is_string($result[$alias][$field])) {
                        $results[$x][$alias][$field] = (integer) $result[$alias][$field];
                    } else if (!array_key_exists($alias, $result) && array_key_exists($field, $result) && is_string($result[$field])) {
                        $results[$x][$field] = (integer) $result[$field];
                    }
                }
            }
        }
        return $results;
    }

    private function getCachedFields(&$Model) {
        $alias = $Model->alias;
        if ($fields = Cache::read('caster_fields',$this->settings[$alias]['cacheConfig'])) {
            $this->fields = $fields;
            if (!array_key_exists($alias,$this->fields)) {
                $this->fields[$alias] = $this->getCurrentFields($Model);
                $this->setCachedFields($Model);
            }
        } else {
            $this->fields[$alias] = $this->getCurrentFields($Model);
            $this->setCachedFields($Model);
        }
    }

    private function setCachedFields(&$Model) {
        $alias = $Model->alias;
        return Cache::write('caster_fields', $this->fields, $this->settings[$alias]['cacheConfig']);
    }

    private function getCurrentFields(&$Model) {
        $schema = $Model->schema();
        $fields = array();
        foreach ($schema as $fieldName => $f) {
            if ($f['type'] == 'integer') {
                $fields[] = $fieldName;
            }
        }
        return $fields;
    }
}
