<?php
class IntCasterBehavior extends ModelBehavior {
    var $fields = array();
    var $cacheConfig = 'default';
    var $Model;

    function setup(&$model, $config = array()) {
        $this->Model =& $model;
        $this->_set($config);
        $this->getCachedFields();
    }

    function afterFind(&$model, $results, $primary) {
        $alias = $model->alias;
        if ($primary && !empty($results[0])) {
            foreach ($results as $x =>$result) {
                foreach ($this->fields[$alias] as $field) {
                    if (array_key_exists($alias, $result) && array_key_exists($field,$result[$alias]) && is_string($result[$alias][$field])) {
                        $results[$x][$alias][$field] = (integer) $result[$alias][$field];
                    } else if (!array_key_exists($alias, $result) && array_key_exists($field, $result) && is_string($result[$field])) {
                        $results[$x][$field] = (integer) $result[$field];
                    }
                }
            }
        }
        return $results;
    }

    private function getCachedFields() {
        $alias = $this->Model->alias;
        if ($fields = Cache::read('caster_fields',$this->cacheConfig)) {
            $this->fields = $fields;
            if (!array_key_exists($alias,$this->fields)) {
                $this->fields[$alias] = $this->getCurrentFields();
                $this->setCachedFields();
            }
        } else {
            $this->fields[$alias] = $this->getCurrentFields();
            $this->setCachedFields();
        }
    }

    private function setCachedFields() {
        $alias = $this->Model->alias;
        return Cache::write('caster_fields',$this->fields,$this->cacheConfig);
    }

    private function getCurrentFields() {
        $schema = $this->Model->schema();
        $fields = array();
        foreach ($schema as $fieldName => $f) {
            if ($f['type'] == 'integer') {
                $fields[] = $fieldName;
            }
        }
        return $fields;
    }
}
