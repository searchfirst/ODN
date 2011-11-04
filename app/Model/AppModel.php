<?php
App::uses('Model', 'Model');
class AppModel extends Model {
    public function find($type = 'first', $query = array()) {
        $results = parent::find($type, $query);

        if ($type === 'all' && array_key_exists('isAjax', $query) && $query['isAjax']) {
            $results = $this->moveModelsToRoot($results);
        }
        return $results;
    }

    public function read($fields = null, $id = null, $moveRoot = false) {
        $data = parent::read($fields, $id);

        if ($data !== false) {
            if ($moveRoot) {
                $data = $this->moveModelToRoot($data);
            }
            return $data;
        } else {
            return false;
        }
    }

    function readRoot() {
        $model = $this->read();
        if (!empty($model)) {
            foreach ($model[$this->alias] as $k => $v) {
                $model[$k] = $v;
                unset($model[$this->alias][$k]);
            }
            unset($model[$this->alias]);
        }
        return $model;
    }

    protected function _findFirst($state, $query, $results = array()) {
        if ($state === 'before') {
            $query['limit'] = 1;
            return $query;
        } elseif ($state === 'after') {
            if (empty($results[0])) {
                return false;
            }
            if (array_key_exists('isAjax', $query) && $query['isAjax']) {
                $results[0] = $this->moveModelToRoot($results[0]);
            }
            return $results[0];
        }
    }

    protected function moveModelToRoot($data) {
        $model = $this->alias;
        if (array_key_exists($model, $data) && is_array($data[$model])) {
            foreach ($data[$model] as $k => $v) {
                $data[$k] = $v;
                unset($data[$model][$k]);
            }
            unset($data[$model]);
        }
        return $data;
    }

    protected function moveModelsToRoot($data) {
        foreach ($data as $k => $v) {
            $data[$k] = $this->moveModelToRoot($v);
        }
        return $data;
    }
        
    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        $data = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive'));

        if (array_key_exists('isAjax', $extra) && $extra['isAjax'] === true) {
            $data = $this->moveModelsToRoot($data);
        }
        return $data;
    }

    function swapFieldData($rowId1,$rowId2,$fieldname) {
        if( ($field1data = $this->field($fieldname,"{$this->name}.id=$rowId1")) &&
            ($field2data = $this->field($fieldname,"{$this->name}.id=$rowId2")) )
                if( ($this->save(array("id"=>$rowId1,$fieldname=>$field2data))) &&
                    ($this->save(array("id"=>$rowId2,$fieldname=>$field1data))) )
                    return true;
                else
                    return false;
        else return false;
    }
}
